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
	
	public function updateSavingsTotal()
	{
		$amount = json_decode(file_get_contents('php://input'), true)["amount"];
		Savings::updateSavingsTotal($amount);
		return Savings::getSavingsTotal();
	}

	/**
	 * update
	 */
	
	public function addFixedToSavings()
	{
		//whereas updateSavingsTotal just changes the total, this function adds or subtracts from the current total.
		include(app_path() . '/inc/functions.php');
		$amount_to_add = json_decode(file_get_contents('php://input'), true)["amount_to_add"];
		Savings::addFixedToSavings($amount_to_add);
		return Savings::getSavingsTotal();
	}

	public function addPercentageToSavings()
	{
		//whereas updateSavingsTotal just changes the total, this function adds or subtracts from the current total.
		$percentage_of_RB = json_decode(file_get_contents('php://input'), true)["percentage_of_RB"];
		Savings::addPercentageToSavings($percentage_of_RB);
		return Savings::getSavingsTotal();
	}

	public function addPercentageToSavingsAutomatically()
	{
		$amount_to_add = json_decode(file_get_contents('php://input'), true)["amount_to_add"];
		Savings::addPercentageToSavingsAutomatically($amount_to_add);
		return Savings::getSavingsTotal();
	}

	public function reverseAutomaticInsertIntoSavings()
	{
		$amount_to_subtract = json_decode(file_get_contents('php://input'), true)["amount_to_subtract"];
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
