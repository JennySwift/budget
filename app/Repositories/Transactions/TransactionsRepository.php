<?php namespace App\Repositories\Transactions;

use App\Models\Budget;
use App\Models\Transaction;
use Auth;
use Carbon\Carbon;
use Debugbar;

/**
 * Class TransactionsRepository
 * @package App\Repositories\Transactions
 */
class TransactionsRepository
{

    /**
     * For Postman:
     *
        {"filter": {

            "types": [],
            "offset":0,
            "num_to_fetch":20,
            "budget": "all",
            "total":"",
            "accounts":[],
            "single_date":"",
            "from_date":"",
            "to_date":"",
            "description":"",
            "merchant":"",
            "tags":[],
            "reconciled":"any"
        }}
     *
     * @param Request $request
     * @return array
     */
    public function filterTransactions($filter)
    {
        $user_id = Auth::user()->id;
        if ($filter['types']) {
            $transaction_type = $filter['types'];
        }

        $transactions = Transaction::where('transactions.user_id', $user_id);
        $totals = Transaction::where('transactions.user_id', $user_id);

        foreach ($filter as $type => $value) {
            if ($value) {
                //================accounts================
                if ($type === "accounts") {
                    $accounts = $value;

                    $transactions = $transactions->whereIn('account_id', $accounts);
                    $totals = $totals->whereIn('account_id', $accounts);
                } //==========type, ie, income, expense, transfer==========
                elseif ($type === "types") {
                    $types = $value;

                    $transactions = $transactions->whereIn('type', $types);
                    $totals = $totals->whereIn('type', $types);
                } // =============dates=============
                elseif ($type === "single_date_sql") {
                    $transactions = $transactions->where('date', $value);
                    $totals = $totals->where('date', $value);
                } elseif ($type === "from_date_sql") {
                    $transactions = $transactions->where('date', '>=', $value);
                    $totals = $totals->where('date', '>=', $value);
                } elseif ($type === "to_date_sql") {
                    $transactions = $transactions->where('date', '<=', $value);
                    $totals = $totals->where('date', '<=', $value);
                } //==========total==========
                elseif ($type === "total") {
                    $transactions = $transactions->where('total', $value);
                    $totals = $totals->where('total', $value);
                } //==========reconciled==========
                elseif ($type === "reconciled") {
                    if ($value !== "any") {
                        $reconciled = $value;
                        $reconciled = $this->convertFromBoolean($reconciled);
                        $transactions = $transactions->where('reconciled', $reconciled);
                        $totals = $totals->where('reconciled', $reconciled);
                    }
                } //==========tags==========
                elseif ($type === "tags") {
                    $tags = $value;

                    $tag_ids = array();
                    foreach ($tags as $tag) {
                        $tag_id = $tag['id'];
                        $tag_ids[] = $tag_id;
                    }

                    foreach ($tag_ids as $tag_id) {
                        $transactions = $transactions->whereHas('tags', function ($q) use ($tag_id) {
                            $q->where('tags.id', $tag_id);
                        });
                        $totals = $totals->whereHas('tags', function ($q) use ($tag_id) {
                            $q->where('tags.id', $tag_id);
                        });
                    }
                } //==========budget==========
                elseif ($type === "budget" && $value !== "all") {
                    if ($value === "none") {
                        $num = ' = 0';
                    } elseif ($value === "single") {
                        $num = ' = 1';
                    } elseif ($value === "multiple") {
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

                    $transactions = $transactions->whereIn('transactions.id', $ids);
                    $totals = $totals->whereIn('transactions.id', $ids);

                } //==========description, merchant==========
                elseif ($type === "description" || $type === "merchant") {
                    $value = '%' . $value . '%';
                    $transactions = $transactions->where($type, 'LIKE', $value);
                    $totals = $totals->where($type, 'LIKE', $value);
                }
            }

        }

        $num_transactions = $totals->count();

        $totals = $totals
            ->select('type', 'reconciled', 'total')
            ->get();

        $transactions = $transactions
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->select('allocated', 'transactions.id', 'date', 'type', 'transactions.account_id AS account_id',
                'accounts.name AS account_name', 'merchant', 'description', 'reconciled', 'total')
            ->skip($filter['offset'])
            ->take($filter['num_to_fetch'])
            ->get();

        //========================get the filter totals========================

        $filter_totals = $this->getFilterTotals($totals);
        $filter_totals['num_transactions'] = $num_transactions;

        //========================get the transactions========================

        $transactions_array = $this->getFilteredTransactions($transactions);

        $result = array(
            "transactions" => $transactions_array,
            "filter_totals" => $filter_totals
        );

        return $result;
    }

    /**
     *
     * @param $transactions
     * @return array
     */
    private function getFilteredTransactions($transactions)
    {
        $transactions_array = array();

        foreach ($transactions as $transaction) {
            $transaction_id = $transaction->id;
            $date = $transaction->date;

            $account = array(
                'id' => $transaction->account_id,
                'name' => $transaction->account_name
            );

            $date = array(
                "user" => $this->convertDate($date, 'user')
            );

            $transaction_model = Transaction::find($transaction->id);

            $transactions_array[] = array(
                'id' => $transaction_id,
                'date' => $date,
                'description' => $transaction->description,
                'merchant' => $transaction->merchant,
                'total' => $transaction->total,
                'account' => $account,
                'reconciled' => $this->convertToBoolean($transaction->reconciled),
                'allocated' => $this->convertToBoolean($transaction->allocated),
                'type' => $transaction->type,
                'tags' => $transaction_model->tags,
                'multiple_budgets' => Transaction::hasMultipleBudgets($transaction_id)
            );

        }

        return $transactions_array;
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
            } elseif ($type === 'expense') {
                $expenses += $total;
            } elseif ($type === 'transfer') {
                if ($total > 0) {
                    $income += $total;
                } elseif ($total < 0) {
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
            'reconciled' => number_format($total_reconciled, 2)
        ];
    }

    /**
     *
     * @param $date
     * @param $for
     * @return string|static
     */
    public function convertDate($date, $for)
    {
        $date = Carbon::createFromFormat('Y-m-d', $date);

        if ($for === 'user') {
            $date = $date->format('d/m/y');
        } elseif ($for === 'sql') {
            $date = $date->format('Y-m-d');
        }

        return $date;
    }

    /**
     *
     * @param $variable
     * @return int
     */
    public function convertFromBoolean($variable)
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
     * @param $variable
     * @return bool
     */
    public function convertToBoolean($variable)
    {
        if ($variable === 1) {
            $variable = true;
        } else {
            $variable = false;
        }

        return $variable;
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