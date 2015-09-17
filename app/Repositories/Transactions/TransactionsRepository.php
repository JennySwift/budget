<?php namespace App\Repositories\Transactions;

use App\Events\TransactionWasCreated;
use App\Models\Account;
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
     * Add budgets to transaction
     * @param $transaction
     * @param $budgets
     * @param $transaction_total
     */
    public function attachBudgets($transaction, $budgets)
    {
        foreach ($budgets as $budget) {
            if (isset($budget['allocated_fixed'])) {
                $this->allocateFixed($transaction, $budget);
            }
            elseif (isset($budget['allocated_percent'])) {
                $this->allocatePercent($transaction, $budget);
            }
            else {
                $transaction->budgets()->attach($budget['id'], [
                    'calculated_allocation' => $transaction->total,
                ]);
            }
        }
    }

    /**
     * Give a transaction a tag with a fixed allocation
     * @param $transaction
     * @param $tag
     */
    public function allocateFixed($transaction, $budget)
    {
        $transaction->budget()->attach($budget['id'], [
            'allocated_fixed' => $budget['allocated_fixed'],
            'calculated_allocation' => $budget['allocated_fixed']
        ]);
    }

    /**
     * Give a transaction a tag with a percentage allocation of the transaction's total
     * @param $transaction
     * @param $tag
     */
    public function allocatePercent($transaction, $budget)
    {
        $transaction->budgets()->attach($budget['id'], [
            'allocated_percent' => $budget['allocated_percent'],
            'calculated_allocation' => $transaction->total / 100 * $budget['allocated_percent'],
        ]);
    }

    /**
     * Insert a transaction in database
     * @param $new_transaction
     * @param $transaction_type
     * @return Transaction
     * @TODO Should happen in your JS,
     */
//    public function insert(array $data)
//    {
//        if ($data['type'] !== Transaction::TYPE_TRANSFER) {
//            $transaction = $this->create($data);
//        } //It's a transfer, so insert two transactions, the from and the to
//        else {
//            $this->create($data, Transaction::DIRECTION_FROM);
//            $this->create($data, Transaction::DIRECTION_TO);
//        }
//
//        return $transaction;
//    }

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
     *
     * @param $transaction
     * @param $data
     */
    private function insertIncomeOrExpenseTransaction($transaction, $data)
    {
        // [1,2,3,4]
        $budgets = $this->defaultAllocation($data['budgets']);

        $transaction->user()->associate(Auth::user());
        $transaction->account()->associate(Account::find($data['account_id']));
        $transaction->save();

        // Insert budgets
        $this->attachBudgets(
            $transaction,
            $budgets
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
            'date' => $data['date']['sql'],
            'description' => $data['description'],
            'merchant' => $data['merchant'],
            'total' => $data['total'],
            'type' => $data['type'],
            'reconciled' => $data['reconciled'],
        ]);
    }

    /**
     * For when a new transaction is entered, so that the calculated allocation
     * for each tag is not 100%, which makes no sense.
     * Give the first tag an allocation of 100% and the rest 0%.
     * @param $tags
     * @return mixed
     */
    public function defaultAllocation($budgets)
    {
        $count = 0;
        foreach ($budgets as $budget) {
            $count++;
            if ($count === 1) {
                $budget['allocated_percent'] = 100;
            }
            else {
                $budget['allocated_percent'] = 0;
            }

            $budgets[$count-1] = $budget;
        }

        return $budgets;
    }

    /**
     *
     * @param $column
     * @param $typing
     * @return mixed
     */
    public function autocompleteTransaction($column, $typing)
    {
        $transactions = Transaction::forCurrentUser()
            ->where($column, 'LIKE', $typing)
            ->limit(50)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->with('account')
            ->with('budgets')
            ->get();

        return $transactions;
    }

}