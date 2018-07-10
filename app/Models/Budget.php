<?php namespace App\Models;

use App;
use App\Traits\ForCurrentUserTrait;
use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Budget
 * @package App\Models
 */
class Budget extends Model
{
    use ForCurrentUserTrait;

    /**
     *
     */
    const TYPE_FIXED = "fixed";
    /**
     *
     */
    const TYPE_FLEX = "flex";
    /**
     *
     */
    const TYPE_UNASSIGNED = "unassigned";

    /**
     * @var array
     */
    protected $fillable = ['type', 'name', 'amount', 'starting_date'];

    /**
     * @var array
     */
    protected $appends = [
        'path'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'starting_date'
    ];

    protected $casts = [
        'starting_date' => 'datetime:Y-m-d',
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
        return route('budgets.show', $this->id);
    }

    /**
     * Count budget's transactions
     * $this->transactions without the parenthesis
     * attaches the transactions to the budgets and I don't want that.
     * @return mixed
     */
    public function getTransactionsCountAttribute()
    {
        return $this->transactions()->count();
    }

    /**
     *
     * @return string
     */
    public function getAmountAttribute()
    {
        return (float)$this->attributes['amount'];
    }

    /**
     *
     * @return string
     */
//    public function getStartingDateAttribute()
//    {
//        if (!$this->isUnassigned()) {
//            dd(Carbon::createFromFormat('Y-m-d', $this->attributes['starting_date']));
//            $date = Carbon::createFromFormat('Y-m-d', $this->attributes['starting_date']);
//            return $date;
//        }
//
//        return null;
//    }

    /**
     *
     * @return string
     */
//    public function getFormattedStartingDateAttribute()
//    {
//        if (!$this->isUnassigned()) {
//            return convertDate($this->attributes['starting_date']);
//        }
//
//        return null;
//    }

    /**
     * Get total spent on a given budget, regardless of the date
     * @return mixed
     */
    public function getSpentAttribute()
    {
        return (float)$this->expenses()
            ->sum('calculated_allocation');
    }

    /**
     * Get total received on a given budget, regardless of the date
     * @return mixed
     */
    public function getReceivedAttribute()
    {
        return (float)$this->incomes()
            ->sum('calculated_allocation');
    }

    /**
     * Get total spent on a given budget before starting date
     * @return mixed
     */
    public function getSpentBeforeStartingDateAttribute()
    {
        if (!$this->isUnassigned()) {
            return (float)$this->expenses()->where('date', '<', $this->attributes['starting_date'])
                ->sum('calculated_allocation');
        }

        return null;
    }

    /**
     * Get total spent on a given budget on or after starting date
     * @return mixed
     */
    public function getSpentOnOrAfterStartingDateAttribute()
    {
        if (!$this->isUnassigned()) {
            $totalSpentOnOrAfterStartingDate = $this->transactions()->where('date', '>=', $this->attributes['starting_date'])
                ->where('type', 'expense')
                ->sum('calculated_allocation');

            return (float)$totalSpentOnOrAfterStartingDate;
        }

        return null;
    }

    /**
     * Get total spent on a given budget during a date range
     * @param $fromDate (format Y-m-d)
     * @param $toDate (format Y-m-d)
     * @return mixed
     */
    public function getSpentInDateRange($fromDate, $toDate)
    {
//        $spentInDateRange = $this->transactions();
//
//        if ($fromDate) {
//            $spentInDateRange = $spentInDateRange->where('date', '>=', $fromDate);
//        }
//        if ($toDate) {
//            $spentInDateRange = $spentInDateRange->where('date', '<=', $toDate);
//        }
//
//        $spentInDateRange = $spentInDateRange
//            ->where('type', 'expense')
//            ->sum('calculated_allocation');
//
//                return (float) $spentInDateRange;

        $transactions = $this->transactions;

        if ($fromDate) {
            $transactions = $transactions->filter(function ($transaction) use ($fromDate) {
                return $transaction->date >= $fromDate;
            });
        }
        if ($toDate) {
            $transactions = $transactions->filter(function ($transaction) use ($toDate) {
                return $transaction->date <= $toDate;
            });
        }
        $transactions = $transactions->filter(function ($transaction) {
            return $transaction->type === 'expense';
        });

        return $transactions->sum('pivot.calculated_allocation');
    }

    /**
     * Get total received on a given budget on or after starting date
     * @return mixed
     */
    public function getReceivedOnOrAfterStartingDateAttribute()
    {
        if (!$this->isUnassigned()) {
            $totalReceivedOnOrAfterStartingDate = $this->transactions()->where('date', '>=', $this->attributes['starting_date'])
                ->where('type', 'income')
                ->sum('calculated_allocation');

            return (float)$totalReceivedOnOrAfterStartingDate;
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

        return null;
    }

    /**
     * Get the cumulative for a budget (C).
     * C is the amount * CMN
     * @return string
     */
    public function getCumulativeAttribute()
    {
        if ($this->isFixed()) {
            return (float)$this->amount * $this->cumulativeMonthNumber;
        }
    }

    /**
     * Get the remaining for a budget (R).
     * R is the cumulative + spentOnOrAfterStartingDate + receivedOnOrAfterStartingDate
     * @return string
     */
    public function getRemainingAttribute()
    {
        if ($this->isFlex()) {
            return $this->calculatedAmount + $this->spentOnOrAfterStartingDate + $this->receivedOnOrAfterStartingDate;
        }

        return (float)$this->cumulative + $this->spentOnOrAfterStartingDate + $this->receivedOnOrAfterStartingDate;
    }

    /**
     * For when a new budget is created,
     * calculated amount is returned with the new budget
     * @param $remainingBalance
     */
    public function getCalculatedAmount($remainingBalance)
    {
//        dd($this->calculatedAmount, $remainingBalance->amount, $this->amount);
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

    /**
     *
     * @return bool
     */
    public function isAssigned()
    {
        return $this->type == $this::TYPE_FIXED || $this->type == $this::TYPE_FLEX;
    }

}
