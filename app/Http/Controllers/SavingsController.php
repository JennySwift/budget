<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Models\Savings;

use Illuminate\Http\Request;

class SavingsController extends Controller {

	/**
	 * select
	 */
	
	/**
	 * insert
	 */
	
	public function updateSavingsTotal(Request $request)
	{
		$amount = $request->get('amount');
		Savings::updateSavingsTotal($amount);
		return Savings::getSavingsTotal();
	}

	/**
	 * update
	 */
	
	public function addFixedToSavings(Request $request)
	{
		//whereas updateSavingsTotal just changes the total, this function adds or subtracts from the current total.
		$amount_to_add = $request->get('amount_to_add');
		Savings::addFixedToSavings($amount_to_add);
		return Savings::getSavingsTotal();
	}

	public function addPercentageToSavings(Request $request)
	{
		//whereas updateSavingsTotal just changes the total, this function adds or subtracts from the current total.
		$percentage_of_RB = $request->get('percentage_of_RB');
		Savings::addPercentageToSavings($percentage_of_RB);
		return Savings::getSavingsTotal();
	}

	public function addPercentageToSavingsAutomatically(Request $request)
	{
		$amount_to_add = $request->get('amount_to_add');
		Savings::addPercentageToSavingsAutomatically($amount_to_add);
		return Savings::getSavingsTotal();
	}

	public function reverseAutomaticInsertIntoSavings(Request $request)
	{
		$amount_to_subtract = $request->get('amount_to_subtract');
		Savings::reverseAutomaticInsertIntoSavings($amount_to_subtract);
		return Savings::getSavingsTotal();
	}
	
	/**
	 * delete
	 */

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
