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
     * @return $this
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'transactions_tags')->withPivot('allocated_fixed',
            'allocated_percent', 'calculated_allocation');
    }

    /**
     * Get tags for one transaction
     * @param $transaction_id
     * @return mixed
     */
    public static function getTags($transaction_id)
    {
        $transaction = Transaction::find($transaction_id);

        $tags = $transaction->tags;

        //Set the allocation type
        foreach ($tags as $tag) {
            $tag->setAllocationType($tag);
        }

        return $tags;
    }

    /**
     *
     * @return mixed
     */
    public static function countTransactions()
    {
        $count = static::where('user_id', Auth::user()->id)
            ->count();

        return $count;
    }

    /**
     * For the new transaction allocation popup.
     * Probably selecting things here that I don't actually need
     * for just the popup.
     * @param $transaction_id
     * @return mixed
     */
    public static function getTransaction($transaction_id)
    {
        $transaction = static::where('transactions.id', $transaction_id)
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->select('allocated', 'transactions.id', 'date', 'type', 'transactions.account_id AS account_id',
                'accounts.name AS account_name', 'merchant', 'description', 'reconciled', 'total', 'date')
            ->first();

        $date = $transaction->date;
        $transaction->user_date = static::convertDate($date, 'user');

        return $transaction;
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
     * @param $column
     * @param $typing
     * @return mixed
     */
    public static function autocompleteTransaction($column, $typing)
    {
        $transactions = static::where($column, 'LIKE', $typing)
            ->where('transactions.user_id', Auth::user()->id)
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->select('transactions.id', 'total', 'account_id', 'accounts.name AS account_name', 'type', 'description',
                'merchant')
            ->limit(50)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($transactions as $transaction) {
            $transaction_id = $transaction->id;
            $account_id = $transaction->account_id;
            $account_name = $transaction->account_name;
            $tags = Tag::getTags($transaction_id);

            $account = array(
                "id" => $account_id,
                "name" => $account_name
            );

            $transaction->account = $account;
            $transaction->tags = $tags;
        }

        return $transactions;
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
        } elseif ($transaction_type === "to") {
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
     * @param $transaction
     */
    public static function updateTransaction($transaction)
    {
        $transaction_id = $transaction['id'];
        $account_id = $transaction['account']['id'];
        $date = $transaction['date']['sql'];
        $merchant = $transaction['merchant'];
        $total = $transaction['total'];
        $tags = $transaction['tags'];
        $description = $transaction['description'];
        $type = $transaction['type'];
        $reconciliation = $transaction['reconciled'];
        $reconciliation = static::convertFromBoolean($reconciliation);

        static::where('id', $transaction_id)
            ->update([
                'account_id' => $account_id,
                'type' => $type,
                'date' => $date,
                'merchant' => $merchant,
                'total' => $total,
                'description' => $description,
                'reconciled' => $reconciliation
            ]);

        //delete all previous tags for the transaction and then add the current ones
        static::deleteAllTagsForTransaction($transaction_id);

        static::insertTags($transaction_id, $tags, $total);
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
}
