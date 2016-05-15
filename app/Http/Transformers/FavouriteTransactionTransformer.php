<?php namespace App\Http\Transformers;

use App\Models\FavouriteTransaction;
use League\Fractal\TransformerAbstract;

/**
 * Class FavouriteTransactionTransformer
 */
class FavouriteTransactionTransformer extends TransformerAbstract
{
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
            'budgets' => $favourite->budgets
        ];

        if ($favourite->account) {
            $array['account'] = [
                'id' => $favourite->account->id,
                'name' => $favourite->account->name
            ];
        }
        if ($favourite->fromAccount) {
            $array['fromAccount'] = [
                'id' => $favourite->fromAccount->id,
                'name' => $favourite->fromAccount->name
            ];
        }
        if ($favourite->toAccount) {
            $array['toAccount'] = [
                'id' => $favourite->toAccount->id,
                'name' => $favourite->toAccount->name
            ];
        }

        return $array;
    }

}