<?php namespace App\Repositories\Transactions;

use App\Models\Transaction;
use Auth;
use DB;
use Debugbar;

/**
 * Class TransactionsRepository
 * @package App\Repositories\Transactions
 */
class TransactionsRepository
{


    /**
     * Insert tags into transaction
     * @param $transaction
     * @param $tags
     * @param $transaction_total
     */
    public function insertTags($transaction, $tags)
    {
        foreach ($tags as $tag) {
            if (isset($tag['allocated_fixed'])) {
                $this->allocateFixed($transaction, $tag);
            }
            elseif (isset($tag['allocated_percent'])) {
                $this->allocatePercent($transaction, $tag);
            }
            else {
                $transaction->tags()->attach($tag['id'], [
                    'calculated_allocation' => $transaction->total,
                ]);
            }
        }
    }

    /**
     * Give a transaction a tag with a fixed allocation
     * @param $transaction
     * @param $tag
     */
    public function allocateFixed($transaction, $tag)
    {
        $transaction->tags()->attach($tag['id'], [
            'allocated_fixed' => $tag['allocated_fixed'],
            'calculated_allocation' => $tag['allocated_fixed']
        ]);
    }

    /**
     * Give a transaction a tag with a percentage allocation of the transaction's total
     * @param $transaction
     * @param $tag
     */
    public function allocatePercent($transaction, $tag)
    {
        $transaction->tags()->attach($tag['id'], [
            'allocated_percent' => $tag['allocated_percent'],
            'calculated_allocation' => $transaction->total / 100 * $tag['allocated_percent'],
        ]);
    }

    /**
     *
     * @param $new_transaction
     * @param $transaction_type
     */
    public function reallyInsertTransaction($new_transaction, $transaction_type)
    {
        $transaction = new Transaction([
            'date' => $new_transaction['date']['sql'],
            'description' => $new_transaction['description'],
            'type' => $new_transaction['type'],
            'reconciled' => convertFromBoolean($new_transaction['reconciled']),
        ]);

        if ($transaction_type === "from") {
            $transaction->account_id = $new_transaction['from_account'];
            $transaction->total = $new_transaction['negative_total'];
        }
        elseif ($transaction_type === "to") {
            $transaction->account_id = $new_transaction['to_account'];
            $transaction->total = $new_transaction['total'];
        }
        elseif ($transaction_type === 'income' || $transaction_type === 'expense') {
            $transaction->account_id = $new_transaction['account'];
            $transaction->merchant = $new_transaction['merchant'];
            $transaction->total = $new_transaction['total'];
        }

        $transaction->user()->associate(Auth::user());
        $transaction->save();

        $tags = $this->defaultAllocation($new_transaction['tags']);

        //inserting tags
        $this->insertTags(
            $transaction,
            $tags
        );

        return $transaction;
    }

    /**
     * For when a new transaction is entered, so that the calculated allocation
     * for each tag is not 100%, which makes no sense.
     * Give the first tag an allocation of 100% and the rest 0%.
     * @param $tags
     * @return mixed
     */
    public function defaultAllocation($tags)
    {
        $count = 0;
        foreach ($tags as $tag) {
            $count++;
            if ($count === 1) {
                $tag['allocated_percent'] = 100;
            }
            else {
                $tag['allocated_percent'] = 0;
            }

            $tags[$count-1] = $tag;
        }

        return $tags;
    }

    /**
     *
     * @param $column
     * @param $typing
     * @return mixed
     */
    public function autocompleteTransaction($column, $typing)
    {
        $transactions = Transaction::where($column, 'LIKE', $typing)
            ->where('transactions.user_id', Auth::user()->id)
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->select('transactions.id', 'total', 'account_id', 'accounts.name AS account_name', 'type', 'description',
                'merchant')
            ->limit(50)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($transactions as $transaction) {
            $transaction_model = Transaction::find($transaction->id);
            $tags = $transaction_model->tags;

            $account = array(
                "id" => $transaction->account_id,
                "name" => $transaction->account_name
            );

            $transaction->account = $account;
            $transaction->tags = $tags;
        }

        return $transactions;
    }

}