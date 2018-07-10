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
     * @var array
     */
    private $fields = ['name', 'type', 'description', 'merchant', 'total'];


    /**
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $favouriteTransactions = FavouriteTransaction::forCurrentUser()->orderBy('name', 'asc')->get();

        return $this->respondIndex($favouriteTransactions, new FavouriteTransactionTransformer);
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $favourite = new FavouriteTransaction($request->only($this->fields));
        $favourite->user()->associate(Auth::user());

        if ($request->get('account_id')) {
            $favourite->account()->associate(Account::findOrFail($request->get('account_id')));
        }

        if ($request->get('from_account_id')) {
            $favourite->fromAccount()->associate(Account::findOrFail($request->get('from_account_id')));
        }
        if ($request->get('to_account_id')) {
            $favourite->toAccount()->associate(Account::findOrFail($request->get('to_account_id')));
        }

        $favourite->save();

        foreach ($request->get('budget_ids') as $id) {
            $favourite->budgets()->attach($id);
        }
        
        return $this->respondStore($favourite, new FavouriteTransactionTransformer);
    }
    
    /**
    * @param Request $request
    * @param FavouriteTransaction $favourite
    * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
    */
    public function update(Request $request, FavouriteTransaction $favourite)
    {
        $data = $this->getData($favourite, $request->only($this->fields));
    
        $favourite->update($data);

        if ($request->has('account_id')) {
            $favourite->account()->associate(Account::findOrFail($request->get('account_id')));
            $favourite->fromAccount()->dissociate();
            $favourite->toAccount()->dissociate();
            $favourite->save();

        }

        if ($request->has('from_account_id')) {
            $favourite->fromAccount()->associate(Account::findOrFail($request->get('from_account_id')));
            $favourite->account()->dissociate();
            $favourite->save();
        }

        if ($request->has('to_account_id')) {
            $favourite->toAccount()->associate(Account::findOrFail($request->get('to_account_id')));
            $favourite->account()->dissociate();
            $favourite->save();
        }

        if ($request->has('budget_ids')) {
            $favourite->budgets()->sync($request->get('budget_ids'));
        }
        
        return $this->respondUpdate($favourite, new FavouriteTransactionTransformer);
    }

    /**
     * 
     * @param Request $request
     * @param FavouriteTransaction $favouriteTransaction
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \ReflectionException
     */
    public function destroy(Request $request, FavouriteTransaction $favouriteTransaction)
    {
        return $this->destroyModel($favouriteTransaction);
    }

}
