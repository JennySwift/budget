<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\InsertAccountRequest;
use App\Http\Requests\Accounts\UpdateAccountRequest;
use App\Http\Transformers\AccountTransformer;
use App\Models\Account;
use Auth;
use Illuminate\Http\Request;

/**
 * Class AccountsController
 * @package App\Http\Controllers
 */
class AccountsController extends Controller
{
    /**
     * @var array
     */
    private $fields = ['name'];

    /**
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $accounts = Account::forCurrentUser()->orderBy('name', 'asc')->get();

        return $this->respondIndex($accounts,
            new AccountTransformer(['includeBalance' => $request->get('includeBalance')]));
    }

    /**
     *
     * @param InsertAccountRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(InsertAccountRequest $request)
    {
        $account = new account($request->only($this->fields));
        $account->user()->associate(Auth::user());
        $account->save();

        return $this->respondStore($account, new accountTransformer);
    }

    /**
     *
     * @param Request $request
     * @param Account $account
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, Account $account)
    {
        return $this->respondShow($account, new AccountTransformer);
    }

    /**
     *
     * @param UpdateAccountRequest $request
     * @param Account $account
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        $data = $this->getData($account, $request->only($this->fields));

        $account->update($data);

        return $this->respondUpdate($account, new AccountTransformer);
    }

    /**
     *
     * @param Request $request
     * @param account $account
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \ReflectionException
     */
    public function destroy(Request $request, account $account)
    {
        return $this->destroyModel($account);
    }
}
