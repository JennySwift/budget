<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Accounts\DeleteAccountRequest;
use App\Http\Requests\Accounts\InsertAccountRequest;
use App\Http\Requests\Accounts\UpdateAccountRequest;
use App\Models\Account;
use Auth;
use DB;
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
     * GET api/accounts
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return response(Account::forCurrentUser()->get(), 200);
    }

    /**
     * POST api/accounts
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
     * GET api/accounts/{accounts}
     * @param $account
     * @return Account
     */
    public function show(Account $account)
    {
        return response($account, 200);
    }

    /**
     * PUT api/accounts/{accounts}
     * @param UpdateAccountRequest $updateAccountRequest
     * @param Account $account
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateAccountRequest $updateAccountRequest, Account $account)
    {
        $account->name = $updateAccountRequest->get('name');
        $account->save();

        return response($account, 200);
    }

    /**
     * Delete an account, only if it belongs to the user
     * DELETE api/accounts/{accounts}
     * @param DeleteAccountRequest $deleteAccountRequest
     * @param $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteAccountRequest $deleteAccountRequest, $account)
    {
        $account->delete();

        return $this->responseNoContent();
    }
}
