<?php namespace App\Repositories\Transactions;

use App\Events\TransactionWasCreated;
use App\Models\Account;
use App\Models\Budget;
use App\Models\Transaction;
use Auth;
use DB;
use Debugbar;
use Illuminate\Http\Request;

/**
 * Class BudgetTransactionRepository
 * @package App\Repositories\Transactions
 */
class BudgetTransactionRepository
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

    /**
     * For mass transaction updating
     * (adding the same budgets to many transactions)
     * Called from TransactionsController update method
     * @param Request $request
     * @param Transaction $transaction
     * @return Transaction
     */
    public function addBudgets(Request $request, Transaction $transaction)
    {
        $budgetIds = $request->get('budget_ids');

        //Prepare the data for the pivot table
        $pivotData = array_fill(0, count($budgetIds), [
            'allocated_percent' => 100,
            'calculated_allocation' => $transaction->total
        ]);

        $syncData  = array_combine($budgetIds, $pivotData);

        $transaction->budgets()->sync($syncData, false);

        return $transaction;
    }

    /**
     * Add budgets to transaction
     * @param Transaction $transaction
     * @param $budgets
     */
    public function attachBudgets(Transaction $transaction, $budgets)
    {
        foreach ($budgets as $budget) {
            if (isset($budget['allocated_fixed'])) {
                $transaction->budgets()->attach($budget['id'], [
                    'allocated_fixed' => $budget['allocated_fixed'],
                    'calculated_allocation' => $budget['allocated_fixed']
                ]);
            }
            elseif (isset($budget['allocated_percent'])) {
                $transaction->budgets()->attach($budget['id'], [
                    'allocated_percent' => $budget['allocated_percent'],
                    'calculated_allocation' => $transaction->total / 100 * $budget['allocated_percent'],
                ]);
            }
            else {
                //Todo: if budget is unassigned, calculated_allocation should be null
                $transaction->budgets()->attach($budget['id'], [
                    'calculated_allocation' => $transaction->total,
                ]);
            }
        }
    }

}