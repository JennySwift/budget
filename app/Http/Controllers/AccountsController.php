<?php namespace App\Http\Controllers;

use App\Exceptions\ModelAlreadyExistsException;
use App\Http\Requests;
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
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $account = new Account(['name' => $request->get('name')]);
        $account->user()->associate(Auth::user());

        checkForDuplicates($account);

        $account->save();

        return response([], 201);
    }

    /**
     *
     * @param Request $request
     */
    public function updateAccountName(Request $request)
    {
        $account = Account::find($request->get('account_id'));
        $account->name = $request->get('account_name');

        checkForDuplicates($account);

        $account->save();
    }

    /**
     *
     * @param Request $request
     */
    public function deleteAccount(Request $request)
    {
        $account = Account::find($request->get('account_id'));
        $account->delete();
    }

}
