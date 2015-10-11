<?php

namespace App\Http\Controllers\API;

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
        $data = $request->except(['account_id', 'budgets']);

        $favouriteTransaction = new FavouriteTransaction($data);
        $favouriteTransaction->account()->associate(Account::find($request->get('account_id')));
        $favouriteTransaction->user()->associate(Auth::user());
        $favouriteTransaction->save();

        return $this->responseCreated($favouriteTransaction);
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
