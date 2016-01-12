<?php namespace App\Repositories\Transactions;

use App\Http\Transformers\FavouriteTransactionTransformer;
use App\Models\FavouriteTransaction;

/**
 * Class FavouriteTransactionsRepository
 * @package App\Repositories\Transactions
 */
class FavouriteTransactionsRepository
{

    /**
     *
     * @return mixed
     */
    public function index()
    {
        $favourites = FavouriteTransaction::forCurrentUser()->get();

        $favourites = createCollection($favourites, new FavouriteTransactionTransformer());
        return transform($favourites)['data'];
    }


}