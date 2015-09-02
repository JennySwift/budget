<?php namespace App\Models;

use App, Cache;
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
        'cumulativeMonthNumber', 'cumulative', 'remaining'
    ];

    //Commenting this out for now because there's so much data I don't need
    //being attached to the budgets for the budget autocomplete in the new transaction
//    protected $with = ['transactions', 'expenses', 'incomes'];

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
     * @return mixed
     */
    public function expenses()
    {
        return $this->transactions()->whereType('expense');
    }

    /**
     *
     * @return mixed
     */
    public function incomes()
    {
        return $this->transactions()->whereType('income');
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
//    public function getCalculatedAmountAttribute()
//    {
//        if($this->isFlex()) {
//            //dd($this->remainingBalance);
//            //return $remainingBalance / 100 * $this->attributes['amount'];
//        }
//
//        return NULL;
//    }

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
        return $this->expenses()->where('date', '<', $this->starting_date)
//                              ->get()
                                ->sum('calculated_allocation');
    }

    /**
     * Get total spent on a given budget on or after starting date
     * @return mixed
     */
    public function getSpentAfterStartingDateAttribute()
    {
        $totalSpentAfterStartingDate = $this->transactions()->where('date', '>=', $this->starting_date)
                                            ->where('type', 'expense')
//                                            ->get()
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
//                                            ->get()
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
