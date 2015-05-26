<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Models\Account;

use Illuminate\Http\Request;

class AccountsController extends Controller {

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

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
