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
     * Increase the user's savings by $amount
     * @param $amount
     * @return int
     */
    public function increase($amount)
    {
        $this->increment('amount', $amount);
    }

    /**
     * Decrease the user's savings by $amount
     * @param $amount
     * @return int
     */
    public function decrease($amount)
    {
        if($this->amount < $amount) {
            throw new \InvalidArgumentException("The amount should not be higher than the savings amount.");
        }

        $this->decrement('amount', $amount);
    }

    /**
     * Get the user's savings total
     * @return mixed
     */
    public static function getSavingsTotal()
    {
        $savings = self::forCurrentUser()->pluck('amount');

        return (float) $savings;
    }
}
