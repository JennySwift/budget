<?php namespace App\Http\Transformers;

use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

/**
 * Class TransactionTransformer
 */
class TransactionTransformer extends TransformerAbstract
{
    /**
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
            //Todo: I think validAllocation and multipleBudgets are causing quite a lot of queries
            'validAllocation' => $transaction->validAllocation,
            'account_id' => $transaction->account_id,
            'account' => [
                'id' => $transaction->account->id,
                'name' => $transaction->account->name
            ],
            'budgets' => $transaction->budgets,
            'multipleBudgets' => (bool) $transaction->multipleBudgets,
            'minutes' => $transaction->minutes
        ];
    }

}