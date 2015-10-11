<?php

namespace App\Http\Controllers\API;

use App\Http\Transformers\FavouriteTransactionTransformer;
use App\Models\Account;
use App\Models\FavouriteTransaction;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FavouriteTransactionsController extends Controller
{
    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['account_id', 'budgets', 'budget_ids']);

        $favouriteTransaction = new FavouriteTransaction($data);
        $favouriteTransaction->account()->associate(Account::find($request->get('account_id')));
        $favouriteTransaction->user()->associate(Auth::user());
        $favouriteTransaction->save();
        $favouriteTransaction->budgets()->attach($request->get('budget_ids'));

        return $this->responseCreatedWithTransformer($favouriteTransaction, new FavouriteTransactionTransformer);
    }

    /**
     *
     * @param FavouriteTransaction $favouriteTransaction
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(FavouriteTransaction $favouriteTransaction)
    {
        $favouriteTransaction->delete();
        return $this->responseNoContent();
    }

}
