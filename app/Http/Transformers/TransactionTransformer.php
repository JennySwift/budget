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
            // @TODO Add remaining field...
            'hasMultipleBudgets' => (bool) $transaction->hasMultipleBudgets()
        ];
    }

}