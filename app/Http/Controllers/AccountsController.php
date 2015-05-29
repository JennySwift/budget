<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Models\Account;

use Illuminate\Http\Request;

class AccountsController extends Controller {

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
	 * select
	 */
	
	public function getAccounts()
	{
		$user_id = Auth::user()->id;
		return Account::where('user_id', $user_id)->orderBy('name', 'asc')->get();
	}

	/**
	 * insert
	 */
	
	public function insertAccount(Request $request)
	{
		$name = $request->get('name');
		Account::insert(['name' => $name, 'user_id' => Auth::user()->id]);
	}

	/**
	 * update
	 */
	
	public function updateAccountName(Request $request)
	{
		$account_id = $request->get('account_id');
		$account_name = $request->get('account_name');
		Account::where('id', $account_id)->update(['name' => $account_name]);
	}

	/**
	 * delete
	 */

	public function deleteAccount(Request $request)
	{
		$account_id = $request->get('account_id');
		Account::where('id', $account_id)->delete();
	}

}
