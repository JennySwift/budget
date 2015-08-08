<?php namespace App\Repositories\Transactions;

use App\Models\Transaction;
use Auth;
use DB;
use Debugbar;

/**
 * Class FilterRepository
 * @package App\Repositories\Transactions
 */
class FilterRepository {

    /**
     * @var
     */
    private $num_transactions;

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
                Debugbar::info('filter', $filter);
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
                elseif ($type === "budget") {
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
        if ($total['in']) {
            $query = $query->where('total', $total['in']);
        }
        if ($total['out']) {
            $query = $query->where('total', '!=', $total['out']);
        }
        return $query;
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
        if ($value['in'] && $value['in'] !== 'all') {
            $query = $this->filterInBudgets($query, $value);
        }
        if ($value['out'] && $value['out'] !== 'none') {
            $query = $this->filterOutBudgets($query, $value);
        }

        return $query;
    }

    private function filterOutBudgets($query, $value)
    {
        if ($value['out'] === "zero") {
            $ids = Transaction::where('user_id', Auth::user()->id)
                ->has('tagsWithBudget', 0)
                ->lists('id');
        }
        elseif ($value['out'] === "single") {
            $ids = Transaction::where('user_id', Auth::user()->id)
                ->has('tagsWithBudget', 1)
                ->lists('id');
        }
        elseif ($value['out'] === "multiple") {
            $ids = Transaction::where('user_id', Auth::user()->id)
                ->has('tagsWithBudget', '>', 1)
                ->lists('id');
        }

        return $query->whereNotIn('transactions.id', $ids);
    }

    private function filterInBudgets($query, $value)
    {
        if ($value['in'] === "zero") {
            $ids = Transaction::where('user_id', Auth::user()->id)
                ->has('tagsWithBudget', 0)
                ->lists('id');
        }
        elseif ($value['in'] === "single") {
            $ids = Transaction::where('user_id', Auth::user()->id)
                ->has('tagsWithBudget', 1)
                ->lists('id');
        }
        elseif ($value['in'] === "multiple") {
            $ids = Transaction::where('user_id', Auth::user()->id)
                ->has('tagsWithBudget', '>', 1)
                ->lists('id');
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
        $query = $this->filterInTags($query, $tags);
        $query = $this->filterOutTags($query, $tags);

        return $query;
    }

    private function filterOutTags($query, $tags)
    {
        if ($tags['out']) {
            //Make an array of the tag ids searched for
            $tag_ids = array();
            foreach ($tags['out'] as $tag) {
                $tag_ids[] = $tag['id'];
            }

            //Add to the $query
            foreach ($tag_ids as $tag_id) {
                $query = $query->whereDoesntHave('tags', function ($q) use ($tag_id) {
                    $q->where('tags.id', $tag_id);
                });
            }
        }

        return $query;
    }

    private function filterInTags($query, $tags)
    {
        if ($tags['in']['and']) {
            //Make an array of the tag ids searched for
            $tag_ids = array();
            foreach ($tags['in']['and'] as $tag) {
                $tag_ids[] = $tag['id'];
            }

            //Add to the $query
            foreach ($tag_ids as $tag_id) {
                $query = $query->whereHas('tags', function ($q) use ($tag_id) {
                    $q->where('tags.id', $tag_id);
                });
            }
        }

        if ($tags['in']['or']) {
            //Make an array of the tag ids searched for
            $tag_ids = array();
            foreach ($tags['in']['or'] as $tag) {
                $tag_ids[] = $tag['id'];
            }

            //Add to the $query
            $query = $query->whereHas('tags', function ($q) use ($tag_ids) {
                $q->whereIn('tags.id', $tag_ids);
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
        if ($type === "single_date") {
            if ($value['in']['sql']) {
                $query = $query->where('date', $value['in']['sql']);
            }
            if ($value['out']['sql']) {
                $query = $query->where('date', '!=', $value['out']['sql']);
            }
        }

        elseif ($type === "from_date") {
            if ($value['in']['sql']) {
                $query = $query->where('date', '>=', $value['in']['sql']);
            }
        }

        elseif ($type === "to_date") {
            if ($value['in']['sql']) {
                $query = $query->where('date', '<=', $value['in']['sql']);
            }
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

}