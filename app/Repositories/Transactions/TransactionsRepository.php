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