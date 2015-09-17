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
    const TYPE_UNASSIGNED = "unassigned";

    protected $fillable = ['type', 'name', 'amount', 'starting_date'];

    protected $appends = [
        'path',
        'formattedStartingDate',
        'spent',
        'received',
        'spentAfterStartingDate',
        'spentBeforeStartingDate',
        'receivedAfterStartingDate',
        'cumulativeMonthNumber',
        'cumulative',
        'remaining',
        'transactionsCount'
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
     * Return the URL of the resource
     * @return string
     */
    public function getPathAttribute()
    {
        return route('api.budgets.show', $this->id);
    }

    /**
     * Count budget's transactions
     * @return mixed
     */
    public function getTransactionsCountAttribute()
    {
        return $this->transactions->count();
    }

    /**
     *
     * @return string
     */
    public function getAmountAttribute()
    {
        return (float) $this->attributes['amount'];
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
        if (!$this->isUnassigned()) {
            return Carbon::createFromFormat('Y-m-d', $this->attributes['starting_date']);
        }
        return null;
    }

    /**
     *
     * @return string
     */
    public function getFormattedStartingDateAttribute()
    {
        if (!$this->isUnassigned()) {
            return convertDate($this->starting_date);
        }
        return NULL;
    }

    /**
     * Get total spent on a given budget, regardless of the date
     * @return mixed
     */
    public function getSpentAttribute()
    {
        return (float) $this->expenses()
            ->sum('calculated_allocation');
    }

    /**
     * Get total received on a given budget, regardless of the date
     * @return mixed
     */
    public function getReceivedAttribute()
    {
        return (float) $this->incomes()
            ->sum('calculated_allocation');
    }

    /**
     * Get total spent on a given budget before starting date
     * @return mixed
     */
    public function getSpentBeforeStartingDateAttribute()
    {
        if (!$this->isUnassigned()) {
            return (float) $this->expenses()->where('date', '<', $this->starting_date)
                ->sum('calculated_allocation');
        }
        return null;
    }

    /**
     * Get total spent on a given budget on or after starting date
     * @return mixed
     */
    public function getSpentAfterStartingDateAttribute()
    {
        if (!$this->isUnassigned()) {
            $totalSpentAfterStartingDate = $this->transactions()->where('date', '>=', $this->starting_date)
                ->where('type', 'expense')
                ->sum('calculated_allocation');

            return (float) $totalSpentAfterStartingDate;
        }
        return null;

    }

    /**
     * Get total received on a given budget on or after starting date
     * @return mixed
     */
    public function getReceivedAfterStartingDateAttribute()
    {
        if (!$this->isUnassigned())
        {
            $totalReceivedAfterStartingDate = $this->transactions()->where('date', '>=', $this->starting_date)
                ->where('type', 'income')
                ->sum('calculated_allocation');

            return (float) $totalReceivedAfterStartingDate;
        }
        return null;

    }

    /**
     * Get the cumulative month number for a budget (CMN).
     * CMN is based on the starting date (CSD) for a budget.
     * @return string
     */
    public function getCumulativeMonthNumberAttribute()
    {
        if (!$this->isUnassigned()) {
            $diff = Carbon::now()->diff($this->starting_date);

            return $diff->format('%y') * 12 + $diff->format('%m') + 1;
        }
        return NULL;
    }

    /**
     * Get the cumulative for a budget (C).
     * C is the amount * CMN
     * @return string
     */
    public function getCumulativeAttribute()
    {
        if($this->isFixed()) {
            return (float) $this->amount * $this->cumulativeMonthNumber;
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

        return (float) $this->cumulative + $this->spentAfterStartingDate + $this->receivedAfterStartingDate;
    }

    /**
     * For when a new budget is created,
     * calculated amount is returned with the new budget
     * @param $remainingBalance
     */
    public function getCalculatedAmount($remainingBalance)
    {
        $this->calculatedAmount = $remainingBalance->amount / 100 * $this->amount;
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

    /**
     *
     * @return bool
     */
    public function isUnassigned()
    {
        return $this->type == $this::TYPE_UNASSIGNED;
    }

}
