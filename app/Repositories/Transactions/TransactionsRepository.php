<?php namespace App\Repositories\Transactions;

use App\Events\TransactionWasCreated;
use App\Models\Account;
use App\Models\Budget;
use App\Models\Transaction;
use Auth;
use DB;
use Debugbar;

/**
 * Class TransactionsRepository
 * @package App\Repositories\Transactions
 */
class TransactionsRepository
{
    /**
     * Give a transaction a budget with a fixed allocation
     * @param $transaction
     * @param $budget
     */
    public function allocateFixed($transaction, $budget)
    {
        $transaction->budget()->attach($budget['id'], [
            'allocated_fixed' => $budget['allocated_fixed'],
            'calculated_allocation' => $budget['allocated_fixed']
        ]);
    }

    /**
     * Give a transaction a budget with a percentage allocation of the transaction's total
     * @param $transaction
     * @param $budget
     */
    public function allocatePercent(Transaction $transaction, $budget)
    {
        $transaction->budgets()->attach($budget['id'], [
            'allocated_percent' => $budget['allocated_percent'],
            'calculated_allocation' => $transaction->total / 100 * $budget['allocated_percent'],
        ]);
    }

    /**
     *
     * @param array $data
     * @return Transaction
     */
    public function create(array $data)
    {
        //Build transaction
        $transaction = $this->newTransaction($data);

        //If transfer
        if($data['type'] === 'transfer') {
            $transaction = $this->insertTransferTransaction($transaction, $data);
        }
        //If income or expense
        else {
            $transaction = $this->insertIncomeOrExpenseTransaction($transaction, $data);
        }

        //Fire event
        event(new TransactionWasCreated($transaction));

        return $transaction;
    }

    /**
     * Budgets should be sent in the form of [1,2,3,4]?
     * @param $transaction
     * @param $data
     */
    private function insertIncomeOrExpenseTransaction($transaction, $data)
    {
        $transaction->user()->associate(Auth::user());
        $transaction->account()->associate(Account::find($data['account_id']));
        $transaction->save();

        // Insert budgets
        $this->attachBudgetsWithDefaultAllocation(
            $transaction,
            $data['budgets']
        );

        return $transaction;
    }

    /**
     *
     * @param $transaction
     * @param $data
     * @return mixed
     */
    private function insertTransferTransaction($transaction, $data)
    {
        switch($data['direction']) {
            case Transaction::DIRECTION_FROM:
                $transaction->total = $data['total'] * -1;
                $account = Account::find($data['account_id']);
                $transaction->account()->associate($account);
                break;
            case Transaction::DIRECTION_TO:
                $transaction->total = $data['total'];
                $account = Account::find($data['account_id']);
                $transaction->account()->associate($account);
                break;
        }

        $transaction->user()->associate(Auth::user());
        $transaction->save();

        return $transaction;
    }

    /**
     * Start to build a new transaction to insert
     * @param $data
     * @return Transaction
     */
    private function newTransaction($data)
    {
        return new Transaction([
            'date' => $data['date'],
            'description' => $data['description'],
            'merchant' => $data['merchant'],
            'total' => $data['total'],
            'type' => $data['type'],
            'reconciled' => $data['reconciled'],
            'minutes' => $data['minutes']
        ]);
    }


    /**
     * For when a new transaction is entered, so that the calculated allocation
     * for each tag is not 100%, which makes no sense.
     * Give the first tag an allocation of 100% and the rest 0%.
     * @param Transaction $transaction
     * @param $budgets
     * @return mixed
     */
    public function attachBudgetsWithDefaultAllocation(Transaction $transaction, $budgets)
    {
        $assignedCount = 0;
        foreach ($budgets as $budget) {
            $budget = Budget::find($budget['id']);

            if ($budget->isAssigned()) {
                $assignedCount++;

                if ($assignedCount === 1) {
                    //Allocate 100% of the transaction to the first assigned budget
                    $transaction->budgets()->attach($budget->id, [
                        'allocated_percent' => 100,
                        'calculated_allocation' => $transaction->total,
                    ]);
                }

                else {
                    //Allocate 0% of the transaction to the other assigned budgets
                    $transaction->budgets()->attach($budget->id, [
                        'allocated_percent' => 0,
                        'calculated_allocation' => 0,
                    ]);
                }
            }

            else {
                //Budget is unassigned. No need to allocate.
                $transaction->budgets()->attach($budget->id);
            }
        }
    }

}