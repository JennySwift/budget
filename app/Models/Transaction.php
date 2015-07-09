<?php namespace App\Models;

use Auth;
use Carbon\Carbon;
use DB;
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
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account');
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
     * Duplicate function from transactions controller
     * @param $variable
     * @return int
     */
    public static function convertFromBoolean($variable)
    {
        if ($variable == 'true') {
            $variable = 1;
        } elseif ($variable == 'false') {
            $variable = 0;
        }

        return $variable;
    }

    /**
     *
     * @param $date
     * @param $for
     * @return string
     */
    public static function convertDate($date, $for)
    {
        if ($for === 'user') {
            $date = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/y');
        }
        elseif ($for === 'sql') {
            $date = Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');

        }

        return $date;
    }

    /**
     *
     * @param $transaction_id
     * @return bool
     */
    public static function hasMultipleBudgets($transaction_id)
    {
        $transaction = Transaction::find($transaction_id);

        $tag_with_budget_counter = 0;
        $multiple_budgets = false;

        foreach ($transaction->tags as $tag) {
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
     * @param $transaction_id
     * @return array
     */
    public static function getAllocationTotals($transaction_id)
    {
        $transaction = Transaction::find($transaction_id);

        $fixed_sum = '-';
        $percent_sum = 0;
        $calculated_allocation_sum = 0;

        foreach ($transaction->tagsWithBudget as $tag) {
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

        $allocation_totals = [
            "fixed_sum" => $fixed_sum,
            "percent_sum" => number_format($percent_sum, 2),
            "calculated_allocation_sum" => number_format($calculated_allocation_sum, 2)
        ];

        return $allocation_totals;
    }

    /**
     * Change the amount that is allocated to the tag, for one transaction
     * @param $allocated_fixed
     * @param $transaction
     * @param $tag_id
     */
    public static function updateAllocatedFixed($allocated_fixed, $transaction, $tag_id)
    {
        $transaction->tags()->updateExistingPivot($tag_id, [
            'allocated_fixed' => $allocated_fixed,
            'allocated_percent' => null,
            'calculated_allocation' => $allocated_fixed
        ]);
    }

    /**
     * Change the percentage of the transaction that is allocated to the tag
     * @param $allocated_percent
     * @param $transaction
     * @param $tag_id
     */
    public static function updateAllocatedPercent($allocated_percent, $transaction, $tag_id)
    {
        $transaction->tags()->updateExistingPivot($tag_id, [
            'allocated_percent' => $allocated_percent,
            'allocated_fixed' => null
        ]);

        static::updateAllocatedPercentCalculatedAllocation($transaction->id, $tag_id);
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
}
