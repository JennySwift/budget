<?php namespace App\Models;

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

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at', 'user_id'];

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
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'transactions_tags')
            ->withPivot('allocated_fixed', 'allocated_percent', 'calculated_allocation');
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
        return route('transactions.show', $this->id);
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

        foreach ($this->tags as $tag) {
            if ($tag->fixed_budget || $tag->flex_budget) {
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
        $fixed_sum = '-';
        $percent_sum = 0;
        $calculated_allocation_sum = 0;

        foreach ($this->tagsWithBudget as $tag) {
            $allocated_fixed = $tag->pivot->allocated_fixed;

            //so that the total displays '-' instead of $0.00 if there were no values to add up.
            if ($allocated_fixed && $fixed_sum === '-') {
                $fixed_sum = 0;
            }

            if ($allocated_fixed) {
                $fixed_sum += $allocated_fixed;
            }

            $percent_sum += $tag->pivot->allocated_percent;
            $calculated_allocation_sum += $tag->pivot->calculated_allocation;
        }

        if ($fixed_sum !== '-') {
            $fixed_sum = number_format($fixed_sum, 2);
        }

        return [
            "fixed_sum" => $fixed_sum,
            "percent_sum" => number_format($percent_sum, 2),
            "calculated_allocation_sum" => number_format($calculated_allocation_sum, 2)
        ];
    }

    /**
     * Change the amount that is allocated to the tag, for one transaction
     * @param $allocated_fixed
     * @param $tag
     */
    public function updateAllocatedFixed($allocated_fixed, $tag)
    {
        //Make sure the fixed allocation is negative for an expense transaction
        if ($this->type === 'expense' && $allocated_fixed > 0) {
            $allocated_fixed*= -1;
        }

        $this->tags()->updateExistingPivot($tag->id, [
            'allocated_fixed' => $allocated_fixed,
            'allocated_percent' => null,
            'calculated_allocation' => $allocated_fixed
        ]);
    }

    /**
     * Get the allocation info for all of the transaction's tags
     * @param $transaction
     * @param $tag
     * @return mixed
     */
//    public function getAllocationInfo($transaction, $tag)
//    {
//        $tag = $transaction->tags()
//            ->where('tag_id', $tag->id)
//            ->first();
//
//        $tag->setAllocationType();
//
//        return $tag;
//    }

    /**
     * Change the amount (percentage of the transaction) that is allocated to the tag
     * @param $allocated_percent
     * @param $tag
     */
    public function updateAllocatedPercent($allocated_percent, $tag)
    {
        $this->setAllocationAutomatically($tag, $allocated_percent);

        $this->tags()->updateExistingPivot($tag->id, [
            'allocated_percent' => $allocated_percent,
            'allocated_fixed' => null,
        ]);

        $this->updateAllocatedPercentCalculatedAllocation($tag->id);

//        return $this->tags;
    }

    /**
     * For when the user gives a tag an allocation of 100%, to automatically give the transaction's
     * other tags an allocation of 0%.
     * $edited_tag is the tag the user gave the allocation to.
     * @param $edited_tag
     * @param $percent
     */
    private function setAllocationAutomatically($edited_tag, $percent)
    {
        if ($percent == 100) {
            $tag_ids = $this->tags()->lists('transactions_tags.tag_id');
            foreach ($tag_ids as $tag_id) {
                $this->tags()->updateExistingPivot($tag_id, [
                    'allocated_percent' => 0,
                    'allocated_fixed' => null
                ]);

                $this->updateAllocatedPercentCalculatedAllocation($tag_id);
            }
        }
    }

    /**
     * Updates calculated_allocation column for one row in transactions_tags,
     * where the tag has been given an allocated percent
     * @param $tag_id
     */
    public function updateAllocatedPercentCalculatedAllocation($tag_id)
    {
        $sql = "UPDATE transactions_tags calculated_allocation JOIN transactions ON transactions.id = transaction_id SET calculated_allocation = transactions.total / 100 * allocated_percent WHERE transaction_id = $this->id AND tag_id = $tag_id;";
        DB::update($sql);
    }
}
