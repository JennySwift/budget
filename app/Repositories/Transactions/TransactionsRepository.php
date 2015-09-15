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
     * Add budgets to transaction
     * @param $transaction
     * @param $budgets
     * @param $transaction_total
     */
    public function attachBudgets($transaction, $budgets)
    {
        foreach ($budgets as $budget) {
            if (isset($budget['allocated_fixed'])) {
                $this->allocateFixed($transaction, $budget);
            }
            elseif (isset($budget['allocated_percent'])) {
                $this->allocatePercent($transaction, $budget);
            }
            else {
                $transaction->budgets()->attach($budget['id'], [
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
    public function allocateFixed($transaction, $budget)
    {
        $transaction->budget()->attach($budget['id'], [
            'allocated_fixed' => $budget['allocated_fixed'],
            'calculated_allocation' => $budget['allocated_fixed']
        ]);
    }

    /**
     * Give a transaction a tag with a percentage allocation of the transaction's total
     * @param $transaction
     * @param $tag
     */
    public function allocatePercent($transaction, $budget)
    {
        $transaction->budgets()->attach($budget['id'], [
            'allocated_percent' => $budget['allocated_percent'],
            'calculated_allocation' => $transaction->total / 100 * $budget['allocated_percent'],
        ]);
    }

    /**
     * Insert a transaction in database
     * @param $new_transaction
     * @param $transaction_type
     * @return Transaction
     * @TODO Should happen in your JS,
     */
//    public function insert(array $data)
//    {
//        if ($data['type'] !== Transaction::TYPE_TRANSFER) {
//            $transaction = $this->create($data);
//        } //It's a transfer, so insert two transactions, the from and the to
//        else {
//            $this->create($data, Transaction::DIRECTION_FROM);
//            $this->create($data, Transaction::DIRECTION_TO);
//        }
//
//        return $transaction;
//    }

    public function create(array $data)
    {
        $transaction = new Transaction([
            'date' => $data['date']['sql'],
            'description' => $data['description'],
            'merchant' => $data['merchant'],
            'total' => $data['total'],
            'type' => $data['type'],
            'reconciled' => $data['reconciled'],
            // @TODO This value should be converted in JS, PHP should receive 0 or 1, to ease validation
        ]);
        if(!array_key_exists('direction', $data)) {
            switch($data['direction']) {
                case Transaction::DIRECTION_FROM:
                    $transaction->total = $data['negative_total'];
                    $account = Account::find($data[Transaction::DIRECTION_FROM]);
                    $transaction->account()->associate($account);
                    break;
                case Transaction::DIRECTION_TO:
                    $transaction->total = $data['total'];
                    $account = Account::find($data[Transaction::DIRECTION_TO]);
                    $transaction->account()->associate($account);
                    break;
            }
        } else {
            // [1,2,3,4]
            $budgets = $this->defaultAllocation($data['budgets']);
            //      Insert budgets
            $this->attachBudgets(
                $transaction,
                $budgets
            );
        }
        $transaction->user()->associate(Auth::user());
        $transaction->save();

        event(new TransactionWasCreated($transaction));

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