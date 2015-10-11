<?php namespace App\Repositories\Transactions;

use App\Http\Transformers\FavouriteTransactionTransformer;
use App\Models\FavouriteTransaction;

/**
 * Class FavouriteTransactionsRepository
 * @package App\Repositories\Transactions
 */
class FavouriteTransactionsRepository
{

    public function index()
    {
        $favourites = FavouriteTransaction::forCurrentUser()->get();

        //Transform
        $favourites = createCollection($favourites, new FavouriteTransactionTransformer());
        return transform($favourites)['data'];
    }


}