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
            'path' => $transaction->path,
            'date' => $transaction->date,
            'userDate' => $transaction->userDate,
            'type' => $transaction->type,
            'description' => $transaction->description,
            'merchant' => $transaction->merchant,
            'total' => $transaction->total,
            'reconciled' => $transaction->reconciled,
            'allocated' => $transaction->allocated,
            'account_id' => $transaction->account_id,
            'account' => [
                'id' => $transaction->account->id,
                'name' => $transaction->account->name
            ],
            'budgets' => $transaction->budgets,
            'multipleBudgets' => (bool) $transaction->multipleBudgets
        ];
    }

}