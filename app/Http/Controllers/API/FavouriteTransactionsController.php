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
    * UPDATE /api/favouritesTransactions/{favouriteTransactions}
    * @param Request $request
    * @param FavouriteTransaction $favourite
    * @return Response
    */
    public function update(Request $request, FavouriteTransaction $favourite)
    {
        // Create an array with the new fields merged
        $data = array_compare($favourite->toArray(), $request->only([
            'name',
            'type',
            'description',
            'merchant',
            'total'
        ]));

        $favourite->update($data);

        if ($request->has('account_id')) {
            $favourite->account()->associate(Account::findOrFail($request->get('account_id')));
            $favourite->save();
        }

        if ($request->has('budget_ids')) {
            $favourite->budgets()->sync($request->get('budget_ids'));
        }

        $favourite = $this->transform($this->createItem($favourite, new FavouriteTransactionTransformer))['data'];
        return response($favourite, Response::HTTP_OK);
    }

    /**
     * DELETE /api/favouriteTransactions/{favouriteTransactions}
     * @param FavouriteTransaction $favourite=
     * @return Response
     */
    public function destroy(FavouriteTransaction $favourite)
    {
        try {
            $favourite->delete();
            return response([], Response::HTTP_NO_CONTENT);
        }
        catch (\Exception $e) {
            //Integrity constraint violation
            if ($e->getCode() === '23000') {
                $message = 'FavouriteTransaction could not be deleted. It is in use.';
            }
            else {
                $message = 'There was an error';
            }
            return response([
                'error' => $message,
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }

}
