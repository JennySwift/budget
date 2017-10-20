<?php namespace App\Http\Transformers;

use App\Models\Account;
use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

/**
 * Class TransactionTransformer
 */
class TransactionTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
//    protected $defaultIncludes = ['account'];

    /**
    *
    * @param Transaction $transaction
    * @return \League\Fractal\Resource\
    */
//    public function includeAccount(Transaction $transaction)
//    {
//        return $this->item($transaction->account, new AccountTransformer);
//    }

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