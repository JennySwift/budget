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
     * POST /api/favouriteTransactions
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $favourite = new FavouriteTransaction($request->only([
            'name',
            'type',
            'description',
            'merchant',
            'total'
        ]));

        $favourite->user()->associate(Auth::user());
        $favourite->account()->associate(Account::find($request->get('account_id')));

        $favourite->save();

        foreach ($request->get('budget_ids') as $id) {
            $favourite->budgets()->attach($id);
        }

        $favourite = $this->transform($this->createItem($favourite, new FavouriteTransactionTransformer))['data'];
        return response($favourite, Response::HTTP_CREATED);
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
