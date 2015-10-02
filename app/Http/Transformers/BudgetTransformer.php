<?php namespace App\Http\Transformers;

use App\Models\Budget;
use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

/**
 * Class BudgetTransformer
 */
class BudgetTransformer extends TransformerAbstract
{
    /**
     * Transform transaction response
     * @param Transaction $transaction
     * @return array
     */
    public function transform(Budget $budget)
    {
        return [
            'path' => $budget->path,
            'name' => $budget->name,
            'amount' => $budget->amount,
            'calculatedAmount' => $budget->calculatedAmount,
            'type' => $budget->type,
            'formattedStartingDate' => $budget->formattedStartingDate,
            'spent' => $budget->spent,
            'received' => $budget->received,
            'spentAfterStartingDate' => $budget->spentAfterStartingDate,
            'spentBeforeStartingDate' => $budget->spentBeforeStartingDate,
            'receivedAfterStartingDate' => $budget->receivedAfterStartingDate,
            'cumulativeMonthNumber' => $budget->cumulativeMonthNumber,
            'cumulative' => $budget->cumulative,
            'remaining' => $budget->remaining,
            'transactionsCount' => $budget->transactionsCount
        ];
    }

}