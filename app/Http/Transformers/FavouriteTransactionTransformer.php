<?php namespace App\Http\Transformers;

use App\Models\FavouriteTransaction;
use League\Fractal\TransformerAbstract;

/**
 * Class FavouriteTransactionTransformer
 */
class FavouriteTransactionTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
//    protected $defaultIncludes = ['account'];

    /**
    *
    * @param FavouriteTransaction $favouriteTransaction
    * @return \League\Fractal\Resource\
    */
//    public function includeAccount(FavouriteTransaction $favouriteTransaction)
//    {
//        return $this->item($favouriteTransaction->account, new AccountTransformer);
//    }

    /**
     *
     * @param FavouriteTransaction $favourite
     * @return array
     */
    public function transform(FavouriteTransaction $favourite)
    {
        $array = [
            'id' => $favourite->id,
            'name' => $favourite->name,
            'type' => $favourite->type,
            'description' => $favourite->description,
            'merchant' => $favourite->merchant,
            'total' => $favourite->total,
            'budgets' => $favourite->budgets,
            //So that date field doesn't go blank when favourite transaction is selected for a new transaction
            'userDate' => ''
        ];

        if ($favourite->account) {
            $array['account'] = [
                'id' => $favourite->account->id,
                'name' => $favourite->account->name,
//                'balance' => $favourite->account->balance
            ];
        }
        if ($favourite->fromAccount) {
            $array['fromAccount'] = [
                'id' => $favourite->fromAccount->id,
                'name' => $favourite->fromAccount->name,
//                'balance' => $favourite->fromAcount->balance
            ];
        }
        if ($favourite->toAccount) {
            $array['toAccount'] = [
                'id' => $favourite->toAccount->id,
                'name' => $favourite->toAccount->name,
//                'balance' => $favourite->toAccount->balance
            ];
        }

        return $array;
    }

}