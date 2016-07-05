<?php

namespace App\Http\Transformers;

use App\Models\Totals\RemainingBalance;
use League\Fractal\TransformerAbstract;

/**
 * Class SidebarTotalTransformer
 * @package App\Http\Transformers
 */
class SidebarTotalTransformer extends TransformerAbstract
{
    /**
     * Sidebar total transformer
     * @param RemainingBalance $remainingBalance
     * @return array
     */
    public function transform(RemainingBalance $remainingBalance)
    {
        return [
            'credit' => $remainingBalance->basicTotals->credit,
            'savings' => $remainingBalance->basicTotals->savings,
            'debit' => $remainingBalance->basicTotals->debit,
            'balance' => $remainingBalance->basicTotals->balance,
            'reconciledSum' => $remainingBalance->basicTotals->reconciledSum,
            'expensesWithoutBudget' => $remainingBalance->basicTotals->expensesWithoutBudget,
            'remainingBalance' => $remainingBalance->amount,
            'remainingFixedBudget' => $remainingBalance->fixedBudgetTotals->remaining,
            'cumulativeFixedBudget' => $remainingBalance->fixedBudgetTotals->cumulative,
            'expensesWithFixedBudgetAfterStartingDate' => $remainingBalance->fixedBudgetTotals->spentOnOrAfterStartingDate,
            'expensesWithFixedBudgetBeforeStartingDate' => $remainingBalance->fixedBudgetTotals->spentBeforeStartingDate,
            'expensesWithFlexBudgetAfterStartingDate' => $remainingBalance->flexBudgetTotals->spentOnOrAfterStartingDate,
            'expensesWithFlexBudgetBeforeStartingDate' => $remainingBalance->flexBudgetTotals->spentBeforeStartingDate
        ];
    }

}