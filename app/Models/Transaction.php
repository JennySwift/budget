<?php namespace App\Models;

use App\Traits\ForCurrentUserTrait;
use Auth;
use DB;
use Debugbar;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * @package App\Models
 */
class Transaction extends Model
{

    use ForCurrentUserTrait;

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at', 'user_id'];

    /**
     * @var array
     */
    protected $appends = ['path'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get tags for one transaction
     * Todo: set the allocation type?
     * @return $this
     */
    public function budgets()
    {
        return $this->belongsToMany('App\Models\Budget', 'budgets_transactions')
            ->withPivot('allocated_fixed', 'allocated_percent', 'calculated_allocation')
            ->orderBy('name', 'asc');
    }

    /**
     * Get the transaction's tags that have a budget
     * @return $this
     */
    public function tagsWithBudget()
    {
        return $this->belongsToMany('App\Models\Tag', 'transactions_tags')
            ->where('budget_id', '!=', 'null')
            ->withPivot('allocated_fixed', 'allocated_percent', 'calculated_allocation');
    }

    /**
     * Get the transaction's tags that have a flex budget
     * @return $this
     */
    public function tagsWithFlexBudget()
    {
        return $this->belongsToMany('App\Models\Tag', 'transactions_tags')
                    ->where('budget_id', 2)
                    ->withPivot('allocated_fixed', 'allocated_percent', 'calculated_allocation');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }

    /**
     * Return the URL of the resource
     * @return string
     */
    public function getPathAttribute()
    {
        return route('api.transactions.show', $this->id);
    }

    /**
     *
     * @return mixed
     */
    public static function getLastTransactionId()
    {
        return static::where('user_id', Auth::user()->id)
            ->max('id');
    }

    /**
     *
     * @param $transaction_id
     */
    public static function deleteAllTagsForTransaction($transaction)
    {
        DB::table('transactions_tags')
            ->where('transaction_id', $transaction->id)
            ->delete();
    }

    /**
     *
     * @param $transaction_id
     * @return bool
     */
    public function hasMultipleBudgets()
    {
        $tag_with_budget_counter = 0;
        $multiple_budgets = false;

        foreach ($this->budgets as $budget) {
            if ($budget->fixed_budget || $budget->flex_budget) {
                //the tag has a budget
                $tag_with_budget_counter++;
            }
        }

        if ($tag_with_budget_counter > 1) {
            //the transaction has more than one tag that has a budget
            $multiple_budgets = true;
        }

        return $multiple_budgets;
    }

    /**
     *
     * @return array
     */
    public function getAllocationTotals()
    {
        $fixedSum = '-';
        $percentSum = 0;
        $calculatedAllocationSum = 0;

        foreach ($this->budgets as $budget) {
            $allocatedFixed = $budget->pivot->allocated_fixed;

            //so that the total displays '-' instead of $0.00 if there were no values to add up.
            if ($allocatedFixed && $fixedSum === '-') {
                $fixedSum = 0;
            }

            if ($allocatedFixed) {
                $fixedSum += $allocatedFixed;
            }

            $percentSum += $budget->pivot->allocated_percent;
            $calculatedAllocationSum += $budget->pivot->calculated_allocation;
        }

        if ($fixedSum !== '-') {
            $fixedSum = number_format($fixedSum, 2);
        }

        return [
            "fixedSum" => $fixedSum,
            "percentSum" => number_format($percentSum, 2),
            "calculatedAllocationSum" => number_format($calculatedAllocationSum, 2)
        ];
    }

    /**
     * Change the amount that is allocated to the tag, for one transaction
     * @param $allocated_fixed
     * @param $tag
     */
    public function updateAllocatedFixed($allocated_fixed, $budget)
    {
        //Make sure the fixed allocation is negative for an expense transaction
        if ($this->type === 'expense' && $allocated_fixed > 0) {
            $allocated_fixed*= -1;
        }

        $this->budgets()->updateExistingPivot($budget->id, [
            'allocated_fixed' => $allocated_fixed,
            'allocated_percent' => null,
            'calculated_allocation' => $allocated_fixed
        ]);
    }

    /**
     * Change the amount (percentage of the transaction) that is allocated to the tag
     * @param $allocated_percent
     * @param $tag
     */
    public function updateAllocatedPercent($allocated_percent, $budget)
    {
        $this->setAllocationAutomatically($budget, $allocated_percent);

        $this->budgets()->updateExistingPivot($budget->id, [
            'allocated_percent' => $allocated_percent,
            'allocated_fixed' => null,
        ]);

        $this->updateAllocatedPercentCalculatedAllocation($budget->id);

        return $this->budgets;
    }

    /**
     * For when the user gives a tag an allocation of 100%, to automatically give the transaction's
     * other tags an allocation of 0%.
     * $edited_tag is the tag the user gave the allocation to.
     * @param $edited_tag
     * @param $percent
     */
    private function setAllocationAutomatically($editedBudget, $percent)
    {
        //Get the other tags for the transaction (not the edited tag)
        $budgetIds = $this->budgets()
            ->where('budgets_transactions.budget_id', '!=', $editedBudget->id)
            ->lists('budgets_transactions.budget_id');

        if ($percent == 100) {
            foreach ($budgetIds as $budgetId) {
                $this->budgets()->updateExistingPivot($budgetId, [
                    'allocated_percent' => 0,
                    'allocated_fixed' => null
                ]);

                $this->updateAllocatedPercentCalculatedAllocation($budgetId);
            }
        }

        //If the transaction has only one other tag apart from the edited tag, automatically set
        //the allocation for that tag to the value that makes the total allocation = 100%.
        elseif (count($budgetIds) === 1) {
            $this->budgets()->updateExistingPivot($budgetIds[0], [
                'allocated_percent' => 100 - $percent,
                'allocated_fixed' => null
            ]);

            $this->updateAllocatedPercentCalculatedAllocation($budgetIds[0]);
        }

    }

    /**
     * Updates calculated_allocation column for one row in transactions_tags,
     * where the tag has been given an allocated percent
     * @param $tag_id
     */
    public function updateAllocatedPercentCalculatedAllocation($budgetId)
    {
        $sql = "UPDATE budgets_transactions calculated_allocation JOIN transactions ON transactions.id = transaction_id SET calculated_allocation = transactions.total / 100 * allocated_percent WHERE transaction_id = $this->id AND budget_id = $budgetId;";
        DB::update($sql);
    }
}
