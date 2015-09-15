<?php namespace App\Http\Transformers;

use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

/**
 * Class TransactionTransformer
 */
class TransactionTransformer extends TransformerAbstract
{
    /**
     * Transform transaction response
     * @param Transaction $transaction
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'id' => $transaction->id,
            'date' => $transaction->date,
            'type' => $transaction->type,
            'description' => $transaction->description,
            'merchant' => $transaction->merchant,
            'total' => $transaction->total,
            'reconciled' => $transaction->reconciled,
            'account_id' => $transaction->account_id,
            'budgets' => $transaction->budgets,
            'hasMultipleBudgets' => (bool) $transaction->hasMultipleBudgets()
        ];
    }

}