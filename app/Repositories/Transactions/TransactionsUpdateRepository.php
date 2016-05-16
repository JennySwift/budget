<?php

namespace App\Repositories\Transactions;

use App\Events\TransactionWasUpdated;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Http\Request;

/**
 * Class TransactionsUpdateRepository
 * @package App\Repositories\Transactions
 */
class TransactionsUpdateRepository
{
    /**
     * Called from TransactionsController update method
     * @param Request $request
     * @param Transaction $transaction
     * @return Transaction
     */
    public function updateTransaction(Request $request, Transaction $transaction)
    {
        $data = array_filter(array_diff_assoc(
            $request->only([
                'date',
                'account_id',
                'description',
                'merchant',
                'total',
                'type',
                'reconciled',
                'allocated',
                'minutes'
            ]),
            $transaction->toArray()
        ), 'removeFalseKeepZeroAndEmptyStrings');

        //Make the total positive if the type has been changed from expense to income
        if (isset($data['type']) && $transaction->type === 'expense' && $data['type'] === 'income') {
            if (isset($data['total']) && $data['total'] < 0) {
                //The user has changed the total as well as the type,
                //but the total is negative and it should be positive
                $data['total'] = $data['total'] * -1;
            }
            else {
                //The user has changed the type but not the total
                $transaction->total = $transaction->total * -1;
                $transaction->save();
            }
        }

        //Make the total negative if the type has been changed from income to expense
        if (isset($data['type']) && $transaction->type === 'income' && $data['type'] === 'expense') {
            if (isset($data['total']) && $data['total'] > 0) {
                //The user has changed the total as well as the type,
                //but the total is positive and it should be negative
                $data['total'] = $data['total'] * -1;
            }
            else {
                //The user has changed the type but not the total
                $transaction->total = $transaction->total * -1;
                $transaction->save();
            }
        }

//        if(empty($data)) {
//            return $this->responseNotModified();
//        }

        //Fire event
        //Todo: update the savings when event is fired
        event(new TransactionWasUpdated($transaction, $data));

        $transaction->update($data);
        $transaction->save();

        $budgets = $request->get('budgets');

        if (isset($budgets)) {
            $transaction->budgets()->detach();
        }

        if ($budgets) {
            $this->attachBudgets($transaction, $budgets);
        }

        return $transaction;
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