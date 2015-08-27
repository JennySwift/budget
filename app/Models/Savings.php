<?php namespace App\Models;

use App\Traits\ForCurrentUserTrait;
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

    use ForCurrentUserTrait;

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
        $savings = self::forCurrentUser()->pluck('amount');

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
    }

    /**
     * Before reversing the automatic insert into savings,
     * calculate the amount to subtract from savings.
     * This is for after an income transaction is deleted.
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
     * Add an amount into savings.
     * For when an income transaction is added.
     * @param $transaction
     */
    public static function add($transaction)
    {
        $percent = 10;
        static::addPercentageToSavingsAutomatically($transaction->total / 100 * $percent);
    }

    /**
     * For when an income transaction total has been edited and decreased,
     * updating the savings accordingly (subtract percentage from savings)
     * @param $previous_total
     * @param $new_total
     */
    public static function calculateAfterDecrease($previous_total, $new_total)
    {
        $diff = $previous_total - $new_total;
        $percent = 10;
        static::reverseAutomaticInsertIntoSavings($diff / 100 * $percent);
    }

    /**
     * For when an income transaction total has been edited and increased,
     * updating the savings accordingly (add percentage to savings)
     * @param $previous_total
     * @param $new_total
     */
    public static function calculateAfterIncrease($previous_total, $new_total)
    {
        $diff = $new_total - $previous_total;
        $percent = 10;
        static::addPercentageToSavingsAutomatically($diff / 100 * $percent);
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
