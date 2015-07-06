<?php namespace App\Models;

use Auth;
use Carbon\Carbon;
use DB;
use Debugbar;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Budget
 * @package App\Models
 */
class Budget extends Model
{
    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->hasMany('App\Models\Tag');
    }

    /**
     * Todo: This needs redoing after refactor
     * @param $CSD
     * @return string
     */
    public static function getCMN($CSD)
    {
        // CMN is cumulative month number
        $CSD = Carbon::createFromFormat('Y-m-d', $CSD);
        $now = Carbon::now();

        $diff = $now->diff($CSD);

        $CMN = $diff->format('%y') * 12 + $diff->format('%m') + 1;

        return $CMN;
    }

    /**
     *
     * @param $transaction_id
     * @return bool
     */
    public static function hasMultipleBudgets($transaction_id)
    {
        $sql = "SELECT tags.fixed_budget, tags.flex_budget FROM transactions_tags JOIN tags ON transactions_tags.tag_id = tags.id WHERE transaction_id = '$transaction_id'";
        $tags = DB::select($sql);

        $tag_with_budget_counter = 0;
        $multiple_budgets = false;

        foreach ($tags as $tag) {
            $fixed_budget = $tag->fixed_budget;
            $flex_budget = $tag->flex_budget;

            if ($fixed_budget || $flex_budget) {
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
     * @param $transaction_id
     * @return array
     */
    public static function getAllocationTotals($transaction_id)
    {
        $rows = DB::table('transactions_tags')
            ->where('transaction_id', $transaction_id)
            ->where('tags.budget_id', '!=', 'null')
            ->join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
            ->select('transactions_tags.transaction_id', 'transactions_tags.tag_id',
                'transactions_tags.allocated_percent', 'transactions_tags.allocated_fixed',
                'transactions_tags.calculated_allocation', 'tags.name', 'tags.fixed_budget', 'tags.flex_budget',
                'tags.budget_id')
            ->get();

        $fixed_sum = '-';
        $percent_sum = 0;
        $calculated_allocation_sum = 0;

        foreach ($rows as $row) {
            $allocated_fixed = $row->allocated_fixed;
            $allocated_percent = $row->allocated_percent;
            $calculated_allocation = $row->calculated_allocation;

            //so that the total displays '-' instead of $0.00 if there were no values to add up.
            if ($allocated_fixed && $fixed_sum === '-') {
                $fixed_sum = 0;
            }

            if ($allocated_fixed) {
                $fixed_sum += $allocated_fixed;
            }

            $percent_sum += $allocated_percent;
            $calculated_allocation_sum += $calculated_allocation;
        }

        if ($fixed_sum !== '-') {
            $fixed_sum = number_format($fixed_sum, 2);
        }

        $percent_sum = number_format($percent_sum, 2);
        $calculated_allocation_sum = number_format($calculated_allocation_sum, 2);

        $allocation_totals = array(
            "fixed_sum" => $fixed_sum,
            "percent_sum" => $percent_sum,
            "calculated_allocation_sum" => $calculated_allocation_sum
        );

        return $allocation_totals;
    }

    /**
     * For one tag.
     * For getting the updated info after updating the allocation for that tag.
     * @param $transaction_id
     * @param $tag_id
     * @return mixed|null
     */
    public static function getAllocationInfo($transaction_id, $tag_id)
    {
        $transaction = Transaction::find($transaction_id);

        $tag = $transaction->tags()
            ->where('tag_id', $tag_id)
            ->first();

        $tag->setAllocationType($tag);

        return $tag;
    }

    /**
     * This either adds or deletes a budget, both using an update query.
     * @param $tag_id
     * @param $budget
     * @param $column
     */
    public static function updateBudget($tag_id, $budget, $column)
    {
        if (!$budget || $budget === "NULL") {
            $budget = null;
            $budget_id = null;
        } else {
            if ($column === "fixed_budget") {
                $budget_id = 1;
            } else {
                $budget_id = 2;
            }
        }

        DB::table('tags')
            ->where('id', $tag_id)
            ->update([$column => $budget, 'budget_id' => $budget_id]);
    }

    /**
     *
     * @param $allocated_fixed
     * @param $transaction_id
     * @param $tag_id
     */
    public static function updateAllocatedFixed($allocated_fixed, $transaction_id, $tag_id)
    {
        DB::table('transactions_tags')
            ->where('transaction_id', $transaction_id)
            ->where('tag_id', $tag_id)
            ->update([
                'allocated_fixed' => $allocated_fixed,
                'allocated_percent' => null,
                'calculated_allocation' => $allocated_fixed
            ]);
    }

    /**
     *
     * @param $allocated_percent
     * @param $transaction_id
     * @param $tag_id
     */
    public static function updateAllocatedPercent($allocated_percent, $transaction_id, $tag_id)
    {
        DB::table('transactions_tags')
            ->where('transaction_id', $transaction_id)
            ->where('tag_id', $tag_id)
            ->update(['allocated_percent' => $allocated_percent, 'allocated_fixed' => null]);

        static::updateAllocatedPercentCalculatedAllocation($transaction_id, $tag_id);
    }

    /**
     * Updates calculated_allocation column for one row in transactions_tags,
     * where the tag has been given an allocated percent
     * @param $transaction_id
     * @param $tag_id
     */
    public static function updateAllocatedPercentCalculatedAllocation($transaction_id, $tag_id)
    {
        $sql = "UPDATE transactions_tags calculated_allocation JOIN transactions ON transactions.id = transaction_id SET calculated_allocation = transactions.total / 100 * allocated_percent WHERE transaction_id = $transaction_id AND tag_id = $tag_id;";
        DB::update($sql);
    }

    /**
     *
     * @param $transaction_id
     * @param $status
     */
    public static function updateAllocationStatus($transaction_id, $status)
    {
        DB::table('transactions')
            ->where('id', $transaction_id)
            ->update(['allocated' => $status]);
    }
}
