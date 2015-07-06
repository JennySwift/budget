<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Account;
use Auth;
use DB;
use Illuminate\Http\Request;

/**
 * Class AccountsController
 * @package App\Http\Controllers
 */
class AccountsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        return view('accounts');
    }

    /**
     *
     * @return mixed
     */
    public function getAccounts()
    {
        return Account::where('user_id', Auth::user()->id)
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     *
     * @param Request $request
     */
    public function insertAccount(Request $request)
    {
        Account::insert([
            'name' => $request->get('name'),
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     *
     * @param Request $request
     */
    public function updateAccountName(Request $request)
    {
        $account = Account::find($request->get('account_id'));
        $account->name = $request->get('account_name');
        $account->save();
    }

    /**
     *
     * @param Request $request
     */
    public function deleteAccount(Request $request)
    {
        Account::where('id', $request->get('account_id'))
            ->delete();
    }

}
