<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Http\Request;

use App\Models\Savings;

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

	

}
