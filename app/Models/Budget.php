<?php namespace App\Models;

use App;
use App\Traits\ForCurrentUserTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Budget
 * @package App\Models
 */
class Budget extends Model
{
    use ForCurrentUserTrait;

    const TYPE_FIXED = "fixed";
    const TYPE_FLEX = "flex";

    protected $fillable = ['type', 'name', 'amount', 'starting_date'];

    protected $appends = [
        'formattedStartingDate', 'spentAfterStartingDate', 'spentBeforeStartingDate', 'receivedAfterStartingDate',
        'cumulativeMonthNumber', 'cumulative', 'remaining', 'calculatedAmount'
    ];

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
    public function transactions()
    {
        return $this->belongsToMany('App\Models\Transaction', 'budgets_transactions')
                    ->withPivot('allocated_fixed', 'allocated_percent', 'calculated_allocation');
    }

    /**
     *
     * @return string
     */
    public function getAmountAttribute()
    {
        return $this->attributes['amount'];
    }

    /**
     *
     * @return string
     */
    public function getCalculatedAmountAttribute()
    {
        if($this->isFlex()) {
            return App::make('App\Models\Totals\RemainingBalance')->calculate() / 100 * $this->attributes['amount'];
        }

        return NULL;
    }

    /**
     *
     * @return string
     */
    public function getStartingDateAttribute()
    {
        return Carbon::createFromFormat('Y-m-d', $this->attributes['starting_date']);
    }

    /**
     *
     * @return string
     */
    public function getFormattedStartingDateAttribute()
    {
        return convertDate($this->starting_date);
    }

    /**
     * Get total spent on a given budget before starting date
     * @return mixed
     */
    public function getSpentBeforeStartingDateAttribute()
    {
        if($this->isFixed()) {
            $totalSpentBeforeStartingDate = $this->transactions()->where('date', '<', $this->starting_date)
                ->where('type', 'expense')
                ->sum('calculated_allocation');

            return $totalSpentBeforeStartingDate;
        }

        return false;
    }

    /**
     * Get total spent on a given budget on or after starting date
     * @return mixed
     */
    public function getSpentAfterStartingDateAttribute()
    {
        $totalSpentAfterStartingDate = $this->transactions()->where('date', '>=', $this->starting_date)
                                            ->where('type', 'expense')
                                            ->sum('calculated_allocation');

        return $totalSpentAfterStartingDate;
    }

    /**
     * Get total received on a given budget on or after starting date
     * @return mixed
     */
    public function getReceivedAfterStartingDateAttribute()
    {
        $totalReceivedAfterStartingDate = $this->transactions()->where('date', '>=', $this->starting_date)
                                            ->where('type', 'income')
                                            ->sum('calculated_allocation');

        return $totalReceivedAfterStartingDate;
    }

    /**
     * Get the cumulative month number for a budget (CMN).
     * CMN is based on the starting date (CSD) for a budget.
     * @return string
     */
    public function getCumulativeMonthNumberAttribute()
    {
        $diff = Carbon::now()->diff($this->starting_date);

        return $diff->format('%y') * 12 + $diff->format('%m') + 1;
    }

    /**
     * Get the cumulative for a budget (C).
     * C is the amount * CMN
     * @return string
     */
    public function getCumulativeAttribute()
    {
        if($this->isFixed()) {
            return $this->amount * $this->cumulativeMonthNumber;
        }
    }

    /**
     * Get the remaining for a budget (R).
     * R is the cumulative + spentAfterStartingDate + receivedAfterStartingDate
     * @return string
     */
    public function getRemainingAttribute()
    {
        if($this->isFlex()) {
            return $this->calculatedAmount + $this->spentAfterStartingDate + $this->receivedAfterStartingDate;
        }

        return $this->cumulative + $this->spentAfterStartingDate + $this->receivedAfterStartingDate;
    }

    /**
     *
     * @return bool
     */
    public function isFixed()
    {
        return $this->type == $this::TYPE_FIXED;
    }

    /**
     *
     * @return bool
     */
    public function isFlex()
    {
        return $this->type == $this::TYPE_FLEX;
    }

}
