<?php

namespace App\Http\Controllers\API;

use App\Http\Transformers\FavouriteTransactionTransformer;
use App\Models\Account;
use App\Models\FavouriteTransaction;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class FavouriteTransactionsController extends Controller
{

    /**
     * GET /api/favouriteTransactions
     * @return Response
     */
    public function index()
    {
        $favourites = FavouriteTransaction::forCurrentUser()->get();
        $favourites = $this->transform($this->createCollection($favourites, new FavouriteTransactionTransformer))['data'];
        return response($favourites, Response::HTTP_OK);
    }

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
