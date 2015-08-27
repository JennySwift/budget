<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Accounts\InsertAccountRequest;
use App\Http\Requests\Accounts\UpdateAccountRequest;
use App\Models\Account;
use Auth;
use DB;
use Illuminate\Http\Request;
use JavaScript;

/**
 * Class AccountsController
 * @package App\Http\Controllers
 */
class AccountsController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return response(Account::forCurrentUser()->get(), 200);
    }

    /**
     *
     * @param InsertAccountRequest $insertAccountRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(InsertAccountRequest $insertAccountRequest)
    {
        $account = new Account(['name' => $insertAccountRequest->get('name')]);
        $account->user()->associate(Auth::user());

        $account->save();

        return response($account, 201);
    }

    /**
     * Demonstration of Model Binding :)
     * GET /accounts/{accounts} => ID of the object, but this is the name of the parameter itself
     * @param $account
     */
    public function show(Account $account)
    {
        dd($account->toArray());
    }

    /**
     * POST /update/accountName (current)
     * PUT /accounts/{accounts} (ideal)
     * @param UpdateAccountRequest $updateAccountRequest
     * @param Account $account
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateAccountRequest $updateAccountRequest, Account $account)
    {
        $account->name = $updateAccountRequest->get('name');
        $account->save();
        //checkForDuplicates($account);

        return response($account, 200);
    }
    
    /**
     * Delete an account, only if it belongs to the user
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $account = Account::forCurrentUser()->findOrFail($id);
        $account->delete();

        return $this->responseNoContent();
    }
}
