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

    private $num_transactions;

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
     * For Postman:
     *
     * {"filter": {
     *
     * "types": [],
     * "offset":0,
     * "num_to_fetch":20,
     * "budget": "all",
     * "total":"",
     * "accounts":[],
     * "single_date":"",
     * "from_date":"",
     * "to_date":"",
     * "description":"",
     * "merchant":"",
     * "tags":[],
     * "reconciled":"any"
     * }}
     *
     * @param Request $request
     * @return array
     */
    public function filterTransactions($filter)
    {
        $query = Transaction::where('transactions.user_id', Auth::user()->id);

        foreach ($filter as $type => $value) {
            if ($value) {

                $query = $this->filterDates($query, $type, $value);
//                Debugbar::info('filter', $filter);
                if ($type === "accounts") {
                    $query = $this->filterAccounts($query, $value);
                }
                elseif ($type === "types") {
                    $query = $this->filterTypes($query, $value);
                }
                elseif ($type === "total") {
                    $query = $this->filterTotal($query, $value);
                }
                elseif ($type === "reconciled") {
                    $query = $this->filterReconciled($query, $value);
                }
                elseif ($type === "tags") {
                    $query = $this->filterTags($query, $value);
                }
                elseif ($type === "budget" && $value !== "all") {
                    $query = $this->filterNumBudgets($query, $value);
                }
                elseif ($type === "description" || $type === "merchant") {
                    $query = $this->filterDescriptionOrMerchant($query, $type, $value);
                }
            }
        }

        $this->num_transactions = $query->count();

        return [
            "transactions" => $this->getFilteredTransactions($this->finishTransactionsQuery($query, $filter)),
            "totals" => $this->getFilterTotals($this->finishTotalsQuery($query))
        ];
    }


    /**
     *
     * @param $query
     * @param $accounts
     * @return mixed
     */
    private function filterAccounts($query, $accounts)
    {
        if ($accounts['in']) {
            $query = $query->whereIn('account_id', $accounts['in']);
        }
        if ($accounts['out']) {
            $query = $query->whereNotIn('account_id', $accounts['out']);
        }
        return $query;
    }

    /**
     * Filter by type (credit, debit, or transfer)
     * @param $query
     * @param $types
     * @return mixed
     */
    private function filterTypes($query, $types)
    {
        if ($types['in']) {
            $query = $query->whereIn('type', $types['in']);
        }
        if ($types['out']) {
            $query = $query->whereNotIn('type', $types['out']);
        }
        return $query;
    }

    /**
     * Filter by total
     * @param $query
     * @param $total
     * @return mixed
     */
    private function filterTotal($query, $total)
    {
        return $query->where('total', $total);
    }

    /**
     * Filter by reconciliation
     * @param $query
     * @param $value
     * @return mixed
     */
    private function filterReconciled($query, $value)
    {
        if ($value !== "any") {
            return $query->where('reconciled', convertFromBoolean($value));
        }

        return $query;
    }

    /**
     * Filter by the number of budgets associated with a transaction
     * @param $query
     * @param $value
     * @return mixed
     */
    private function filterNumBudgets($query, $value)
    {
        if ($value === "none") {
            $num = ' = 0';
        }
        elseif ($value === "single") {
            $num = ' = 1';
        }
        elseif ($value === "multiple") {
            $num = ' > 1';
        }

        //first, find get all the transactions that have x number of budgets
        $sql = "select id from transactions where transactions.user_id = " . Auth::user()->id . " and (select count(*) from tags inner join transactions_tags on tags.id = transactions_tags.tag_id
	                where transactions_tags.transaction_id = transactions.id
	                and tags.budget_id is not null)" . $num;

        $transactions_with_x_budgets = DB::select($sql);

        //format transactions_with_one_budget into a nice array
        $ids = array();
        foreach ($transactions_with_x_budgets as $transaction) {
            $id = $transaction->id;
            $ids[] = $id;
        }

        return $query->whereIn('transactions.id', $ids);
    }

    /**
     *
     * @param $query
     * @param $type
     * @param $value
     * @return
     */
    private function filterDescriptionOrMerchant($query, $type, $value)
    {
        if ($value['in']) {
            $query = $query->where($type, 'LIKE', '%' . $value['in'] . '%');
        }
        if ($value['out']) {
            $query = $query->where($type, 'NOT LIKE', '%' . $value['out'] . '%');
        }
        return $query;
    }

    /**
     * Filter the transactions for those that have all the tags searched for
     * @param $query
     * @param $tags
     * @return mixed
     */
    private function filterTags($query, $tags)
    {
        //Make an array of the tag ids searched for
        $tag_ids = array();
        foreach ($tags as $tag) {
            $tag_ids[] = $tag['id'];
        }

        //Add to the $query
        foreach ($tag_ids as $tag_id) {
            $query = $query->whereHas('tags', function ($q) use ($tag_id) {
                $q->where('tags.id', $tag_id);
            });
        }

        return $query;
    }

    /**
     * Filter by date
     * @param $query
     * @param $type
     * @param $value
     */
    private function filterDates($query, $type, $value)
    {
        if ($type === "single_date_sql") {
            $query = $query->where('date', $value);
        }
        elseif ($type === "from_date_sql") {
            $query = $query->where('date', '>=', $value);
        }
        elseif ($type === "to_date_sql") {
            $query = $query->where('date', '<=', $value);
        }

        return $query;
    }

    /**
     * Get the transactions after putting together the query
     * @param $query
     * @param $filter
     * @return mixed
     */
    private function finishTransactionsQuery($query, $filter)
    {
        return $query
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->skip($filter['offset'])
            ->take($filter['num_to_fetch'])
            ->with('tags')
            ->with('account')
            ->get();
    }

    /**
     * Get the totals after putting together the query
     * @param $query
     * @return mixed
     */
    private function finishTotalsQuery($query)
    {
        return $query
            ->select('type', 'reconciled', 'total')
            ->get();
    }

    /**
     *
     * @param $transactions
     * @return mixed
     */
    private function getFilteredTransactions($transactions)
    {
        foreach ($transactions as $transaction) {
            $date = [
                'user' => convertDate($transaction->date, 'user')
            ];

            $transaction->date = $date;
            $transaction->reconciled = convertToBoolean($transaction->reconciled);
            $transaction->allocated = convertToBoolean($transaction->allocated);
            $transaction->multiple_budgets = $transaction->hasMultipleBudgets();
        }

        return $transactions;
    }

    /**
     *
     * @param $totals
     * @return array
     */
    private function getFilterTotals($totals)
    {
        $income = 0;
        $expenses = 0;
        $total_reconciled = 0;

        foreach ($totals as $transaction) {
            $total = $transaction->total;
            $type = $transaction->type;
            $reconciled = $transaction->reconciled;

            if ($type === 'income') {
                $income += $total;
            }
            elseif ($type === 'expense') {
                $expenses += $total;
            }
            elseif ($type === 'transfer') {
                if ($total > 0) {
                    $income += $total;
                }
                elseif ($total < 0) {
                    $expenses += $total;
                }
            }
            if ($reconciled == 1) {
                $total_reconciled += $total;
            }
        }

        $balance = $income + $expenses;

        return [
            'income' => number_format($income, 2),
            'expenses' => number_format($expenses, 2),
            'balance' => number_format($balance, 2),
            'reconciled' => number_format($total_reconciled, 2),
            'num_transactions' => $this->num_transactions
        ];
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