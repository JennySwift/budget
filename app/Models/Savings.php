<?php namespace App\Models;

use Auth;
use DB;
use Debugbar;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Savings
 * @package App\Models
 */
class Savings extends Model
{

    /**
     * @var string
     */
    protected $table = 'savings';

    /**
     *
     * @return mixed
     */
    public static function getSavingsTotal()
    {
        $savings = DB::table('savings')
            ->where('user_id', Auth::user()->id)
            ->pluck('amount');

        return $savings;
    }

    /**
     *
     * @param $amount
     */
    public static function updateSavingsTotal($amount)
    {
        DB::table('savings')
            ->where('user_id', Auth::user()->id)
            ->update(['amount' => $amount]);
    }

    /**
     * Whereas updateSavingsTotal just changes the total,
     * this function adds or subtracts from the current total.
     * @param $amount_to_add
     */
    public static function addFixedToSavings($amount_to_add)
    {
        DB::table('savings')
            ->where('user_id', Auth::user()->id)
            ->increment('amount', $amount_to_add);
    }

    /**
     *
     * @param $amount_to_add
     */
    public static function addPercentageToSavingsAutomatically($amount_to_add)
    {
        DB::table('savings')
            ->where('user_id', Auth::user()->id)
            ->increment('amount', $amount_to_add);
    }

    /**
     *
     * @param $amount_to_subtract
     */
    public static function reverseAutomaticInsertIntoSavings($amount_to_subtract)
    {
        DB::table('savings')
            ->where('user_id', Auth::user()->id)
            ->decrement('amount', $amount_to_subtract);
    }

    /**
     *
     * @param $percentage_of_RB
     */
    public static function addPercentageToSavings($percentage_of_RB)
    {
        $RB = getRB();
        $amount_to_add = $RB / 100 * $percentage_of_RB;

        DB::table('savings')
            ->where('user_id', Auth::user()->id)
            ->increment('amount', $amount_to_add);
    }
}
