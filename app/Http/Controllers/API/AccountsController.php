<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Accounts\DeleteAccountRequest;
use App\Http\Requests\Accounts\InsertAccountRequest;
use App\Http\Requests\Accounts\UpdateAccountRequest;
use App\Http\Transformers\AccountTransformer;
use App\Models\Account;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JavaScript;
use Pusher;

/**
 * Class AccountsController
 * @package App\Http\Controllers
 */
class AccountsController extends Controller
{

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $accounts = Account::forCurrentUser()->orderBy('name', 'asc')->get();

        $accounts = $this->transform($this->createCollection($accounts, new AccountTransformer(['includeBalance' => $request->get('includeBalance')])))['data'];
        return response($accounts, Response::HTTP_OK);
    }

    /**
     * POST /api/accounts/{accounts}
     * @param InsertAccountRequest $request
     * @return Response
     */
    public function store(InsertAccountRequest $request)
    {
        $account = new Account($request->only(['name']));
        $account->user()->associate(Auth::user());
        $account->save();

        $account = $this->transform($this->createItem($account, new AccountTransformer))['data'];

        $pusher = new Pusher(env('PUSHER_PUBLIC_KEY'), env('PUSHER_SECRET_KEY'), env('PUSHER_APP_ID'));

        $data = 'account created!';

        $pusher->trigger('myChannel', 'accountCreated', $data);

        return response($account, Response::HTTP_CREATED);
    }

    /**
     * GET /api/accounts/{accounts}
     * @param Account $account
     * @return Response
     */
    public function show(Account $account)
    {
        $account = $this->transform($this->createItem($account, new AccountTransformer))['data'];
        return response($account, Response::HTTP_OK);
    }
    
    /**
    * UPDATE /api/accounts/{accounts}
    * @param UpdateAccountRequest $request
    * @param Account $account
    * @return Response
    */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        // Create an array with the new fields merged
        $data = array_compare($account->toArray(), $request->only([
            'name'
        ]));

        $account->update($data);

        $account = $this->transform($this->createItem($account, new AccountTransformer))['data'];
        return response($account, Response::HTTP_OK);
    }

    /**
     * DELETE /api/accounts/{accounts}
     * @param DeleteAccountRequest $deleteAccountRequest
     * @param Account $account
     * @return Response
     */
    public function destroy(DeleteAccountRequest $deleteAccountRequest, Account $account)
    {
        try {
            $account->delete();
            return response([], Response::HTTP_NO_CONTENT);
        }
        catch (\Exception $e) {
            //Integrity constraint violation
            if ($e->getCode() === '23000') {
                $message = 'Account could not be deleted. It is in use.';
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
