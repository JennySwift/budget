<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Debugbar;

class Savings extends Model {

	protected $table = 'savings';

	/**
	 * define relationships
	 */

	/**
	 * select
	 */
	
	public static function getSavingsTotal()
	{
		$savings = DB::table('savings')
			->where('user_id', Auth::user()->id)
			->pluck('amount');

		return $savings;
	}

	/**
	 * insert
	 */
	
	/**
	 * update
	 */
	
	public static function updateSavingsTotal($amount)
	{
		DB::table('savings')
			->where('user_id', Auth::user()->id)
			->update(['amount' => $amount]);
	}

	public static function addFixedToSavings($amount_to_add)
	{
		//whereas updateSavingsTotal just changes the total, this function adds or subtracts from the current total.
		DB::table('savings')
			->where('user_id', Auth::user()->id)
			->increment('amount', $amount_to_add);
			// ->update(['amount' => 'amount' + $amount_to_add]);
	}

	public static function addPercentageToSavingsAutomatically($amount_to_add)
	{
		DB::table('savings')
			->where('user_id', Auth::user()->id)
			->increment('amount', $amount_to_add);
	}

	public static function reverseAutomaticInsertIntoSavings($amount_to_subtract)
	{
		DB::table('savings')
			->where('user_id', Auth::user()->id)
			->decrement('amount', $amount_to_subtract);
	}

	public static function addPercentageToSavings($percentage_of_RB)
	{
		$RB = getRB();
		$amount_to_add = $RB / 100 * $percentage_of_RB;

		Debugbar::info('RB: ' . $RB);
		Debugbar::info('percentage_of_RB: ' . $percentage_of_RB);
		Debugbar::info('amount_to_add: ' . $amount_to_add);

		DB::table('savings')
			->where('user_id', Auth::user()->id)
			->increment('amount', $amount_to_add);
	}

	/**
	 * delete
	 */

}
