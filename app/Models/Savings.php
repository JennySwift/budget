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
     * @var array
     */
    protected $fillable = ['amount'];

    /**
     * @var string
     */
    protected $table = 'savings';

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     *
     * @return mixed
     */
    public static function getSavingsTotal()
    {
        $savings = Savings::where('user_id', Auth::user()->id)
            ->pluck('amount');

        return $savings;
    }

    /**
     * Whereas updateSavingsTotal just changes the total,
     * this function adds or subtracts from the current total.
     * @param $amount_to_add
     */
    public static function addFixedToSavings($amount_to_add)
    {
        Savings::where('user_id', Auth::user()->id)
            ->increment('amount', $amount_to_add);
    }

    /**
     *
     * @param $amount_to_add
     */
    public static function addPercentageToSavingsAutomatically($amount_to_add)
    {
        Savings::where('user_id', Auth::user()->id)
            ->increment('amount', $amount_to_add);
    }

    /**
     *
     * @param $amount_to_subtract
     */
    public static function reverseAutomaticInsertIntoSavings($amount_to_subtract)
    {
        Savings::where('user_id', Auth::user()->id)
            ->decrement('amount', $amount_to_subtract);

//        return $this->budgetService->getBasicAndBudgetTotals();
    }

    /**
     * Before reversing the automatic insert into savings,
     * calculate the amount to subtract from savings.
     * @param $transaction
     */
    public static function calculateAmountToSubtract($transaction)
    {
        //This value will change. Just for developing purposes.
        $percent = 10;
        $amount_to_subtract = $transaction->total / 100 * $percent;
        static::reverseAutomaticInsertIntoSavings($amount_to_subtract);
    }


    /**
     *
     * @param $percentage_of_RB
     */
    public static function addPercentageToSavings($percentage_of_RB)
    {
        $RB = getRB();
        $amount_to_add = $RB / 100 * $percentage_of_RB;

        Savings::where('user_id', Auth::user()->id)
            ->increment('amount', $amount_to_add);
    }
}
