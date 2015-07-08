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
    protected $fillable = ['description', 'merchant', 'account', 'reconciled', 'allocated'];

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
        return $this->belongsToMany('App\Models\Tag', 'transactions_tags')->withPivot('allocated_fixed',
            'allocated_percent', 'calculated_allocation');
    }

    /**
     *
     * @return mixed
     */
    public static function getLastTransactionId()
    {
        $last_transaction_id = static::where('user_id', Auth::user()->id)
            ->max('id');

        return $last_transaction_id;
    }

    /**
     *
     * @param $new_transaction
     * @param $transaction_type
     */
    public static function insertTransaction($new_transaction, $transaction_type)
    {
        $user_id = Auth::user()->id;
        $date = $new_transaction['date']['sql'];
        $description = $new_transaction['description'];
        $type = $new_transaction['type'];
        $reconciled = $new_transaction['reconciled'];
        $reconciled = static::convertFromBoolean($reconciled);
        $tags = $new_transaction['tags'];

        if ($transaction_type === "from") {
            $from_account = $new_transaction['from_account'];
            $total = $new_transaction['negative_total'];

            static::insert([
                'account_id' => $from_account,
                'date' => $date,
                'total' => $total,
                'description' => $description,
                'type' => $type,
                'reconciled' => $reconciled,
                'user_id' => Auth::user()->id
            ]);
        }
        elseif ($transaction_type === "to") {
            $to_account = $new_transaction['to_account'];
            $total = $new_transaction['total'];

            static::insert([
                'account_id' => $to_account,
                'date' => $date,
                'total' => $total,
                'description' => $description,
                'type' => $type,
                'reconciled' => $reconciled,
                'user_id' => Auth::user()->id
            ]);
        } elseif ($transaction_type === 'income' || $transaction_type === 'expense') {
            $account = $new_transaction['account'];
            $merchant = $new_transaction['merchant'];
            $total = $new_transaction['total'];

            static::insert([
                'account_id' => $account,
                'date' => $date,
                'merchant' => $merchant,
                'total' => $total,
                'description' => $description,
                'type' => $type,
                'reconciled' => $reconciled,
                'user_id' => Auth::user()->id
            ]);
        }

        //inserting tags
        $last_transaction_id = static::getLastTransactionId($user_id);
        static::insertTags($last_transaction_id, $tags, $total);
    }

    /**
     * Insert tags into transaction
     * @param $transaction_id
     * @param $tags
     * @param $transaction_total
     */
    public static function insertTags($transaction_id, $tags, $transaction_total)
    {
        foreach ($tags as $tag) {
            $tag_id = $tag['id'];

            if (isset($tag['allocated_fixed'])) {
                $tag_allocated_fixed = $tag['allocated_fixed'];
                $calculated_allocation = $tag_allocated_fixed;

                DB::table('transactions_tags')
                    ->insert([
                        'transaction_id' => $transaction_id,
                        'tag_id' => $tag_id,
                        'allocated_fixed' => $tag_allocated_fixed,
                        'calculated_allocation' => $calculated_allocation,
                        'user_id' => Auth::user()->id
                    ]);

            } elseif (isset($tag['allocated_percent'])) {
                $tag_allocated_percent = $tag['allocated_percent'];
                $calculated_allocation = $transaction_total / 100 * $tag_allocated_percent;

                DB::table('transactions_tags')
                    ->insert([
                        'transaction_id' => $transaction_id,
                        'tag_id' => $tag_id,
                        'allocated_percent' => $tag_allocated_percent,
                        'calculated_allocation' => $calculated_allocation,
                        'user_id' => Auth::user()->id
                    ]);

            } else {
                $calculated_allocation = $transaction_total;

                DB::table('transactions_tags')
                    ->insert([
                        'transaction_id' => $transaction_id,
                        'tag_id' => $tag_id,
                        'calculated_allocation' => $calculated_allocation,
                        'user_id' => Auth::user()->id
                    ]);

            }
        }
    }

    /**
     *
     * @param $transaction_id
     */
    public static function deleteAllTagsForTransaction($transaction_id)
    {
        DB::table('transactions_tags')
            ->where('transaction_id', $transaction_id)
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
        } elseif ($for === 'sql') {
            dd('elseif');
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
        $tags = DB::table('transactions_tags')
            ->where('transaction_id', $transaction_id)
            ->join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
            ->select('tags.fixed_budget', 'tags.flex_budget')
            ->get();

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
            ->select(
                'transactions_tags.transaction_id',
                'transactions_tags.tag_id',
                'transactions_tags.allocated_percent',
                'transactions_tags.allocated_fixed',
                'transactions_tags.calculated_allocation',
                'tags.name',
                'tags.fixed_budget',
                'tags.flex_budget',
                'tags.budget_id'
            )
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
     * Change the amount that is allocated to the tag, for one transaction
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
     * Change the percentage of the transaction that is allocated to the tag
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
}
