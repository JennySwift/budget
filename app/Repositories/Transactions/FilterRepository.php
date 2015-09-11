<?php namespace App\Repositories\Transactions;

use App\Models\Transaction;
use App\Models\Totals\FilterTotals;
use Auth;
use DB;
use Debugbar;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

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
     * @var
     */
    private $filters;

    /**
     * For Postman:
     *
     * {"filter": {
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
     * @var array
     *
     */
    protected $defaults = [
        "budget" => [
            "in" => "all",
            "out" => ""
        ],
        "total" => [
            "in" => "",
            "out" => ""
        ],
        "types" => [
            "in" => [],
            "out" => []
        ],
        "accounts" => [
            "in" => [],
            "out" => []
        ],
        "single_date" => [
            "in" => [
                "user" => "",
                "sql" => ""
            ],
            "out" => [
                "user" => "",
                "sql" => ""
            ],
        ],
        "from_date" => [
            "in" => [
                "user" => "",
                "sql" => ""
            ],
            "out" => [
                "user" => "",
                "sql" => ""
            ],
        ],
        "to_date" => [
            "in" => [
                "user" => "",
                "sql" => ""
            ],
            "out" => [
                "user" => "",
                "sql" => ""
            ],
        ],
        "description" => [
              "in" => "",
              "out" => ""
        ],
        "merchant" => [
              "in" => "",
              "out" => ""
        ],
        "tags" => [
            "in" => [
                "and" => [],
                "or" => []
            ],
            "out" => []
        ],
        "reconciled" => "any",
        "offset" => 0,
        "num_to_fetch" => 30
    ];

    /**
     * Filter the transactions
     * @param array $filters
     * @return array
     */
    public function filterTransactions(array $filters = [])
    {
        // Merge the argument with the defaults
        $this->filters = array_merge($this->defaults, $filters);

        // Prepare the query
        $query = Transaction::where('transactions.user_id', Auth::user()->id);

        // Apply filters to the transaction query
        foreach ($this->filters as $type => $value) {
            Debugbar::info('filter', $filters);

            switch($type) {
                case "single_date":
                case "from_date":
                case "to_date":
                    $query = $this->filterDates($query, $type, $value);
                    break;
                case "accounts":
                    $query = $this->filterAccounts($query, $value);
                    break;
                case "types":
                    $query = $this->filterTypes($query, $value);
                    break;
                case "total":
                    $query = $this->filterTotal($query, $value);
                    break;
                case "reconciled":
                    $query = $this->filterReconciled($query, $value);
                    break;
//                case "tags":
//                    $query = $this->filterTags($query, $value);
//                    break;
                case "budget":
                    $query = $this->filterNumBudgets($query, $value);
                    break;
                case "description":
                case "merchant":
                    $query = $this->filterDescriptionOrMerchant($query, $type, $value);
                    break;
                default:
                    // @TODO If nothing matches, throw an exception!!
            }
        }

        $this->num_transactions = $this->countTransactions($query);

        // If I didn't clone here, I couldn't reuse the original query because it would get modified
//        $transactionsQuery = clone $query;
//        $totalsQuery = clone $query;
//        $graphTotalsQuery = clone $query;

//        return $this->getGraphTotals($graphTotalsQuery);

        return [
            "transactions" => $this->getFilteredTransactions($this->finishTransactionsQuery($query, $this->filters)),
            "totals" => $this->getFilterTotals($this->finishTotalsQuery($query)),
            "graph_totals" => $this->getGraphTotals($query)
        ];
    }

    private function getGraphTotals($query)
    {
        $minDate = $query->min('date');
        $maxDate = $query->max('date');
        $minDate = Carbon::createFromFormat('Y-m-d', $minDate)->startOfMonth();
        $maxDate = Carbon::createFromFormat('Y-m-d', $maxDate)->startOfMonth();

        $monthsTotals = [];

        $date = $maxDate;

        while ($minDate <= $date) {
            $monthsTotals[] = $this->monthTotals($query, $date);
            $date = $date->subMonths(1);

        }

        return [
            'monthsTotals' => $monthsTotals,
            'maxTotal' => $this->getMax($monthsTotals)
        ];
    }

    /**
     * For the graph for the months, getting the max value, either income or expense,
     * from the totals of the month, in order to calculate
     * the height of the bars
     */
    private function getMax($monthsTotals)
    {
        //These worked before I did the formatting in FilterTotals.php
//        $maxIncome = max(collect($monthsTotals)->lists('income'));
//        $maxExpenses = min(collect($monthsTotals)->lists('expenses')) * -1;

        $maxIncome = max($this->getRawValues($monthsTotals, 'income'));
        $maxExpenses = min($this->getRawValues($monthsTotals, 'expenses')) * -1;

        return max($maxIncome, $maxExpenses);
    }

    /**
     *
     */
    private function getRawValues($array, $property)
    {
        $rawValues = [];

        foreach ($array as $item) {
            $rawValues[] = $item->{$property};
        }

        return $rawValues;
    }

    private function monthTotals($query, $date)
    {
        $queryClone = clone $query;
        $lastMonthTransactions = $queryClone
            ->whereMonth('date', '=', $date->month)
            ->whereYear('date', '=', $date->year)
            ->get();

        $monthTotals = $this->getFilterTotals($lastMonthTransactions);
        $monthTotals->month = $date->format("M Y");

        return $monthTotals;
    }

    /**
     * Get the totals after putting together the query
     * @param $query
     * @return mixed
     */
    private function finishTotalsQuery($query)
    {
//        dd($query->toSql());
        return $query
            ->select('date', 'type', 'reconciled', 'total')
            ->orderBy('date', 'desc')
            ->get();
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

        //todo: format these values again elsewhere. I need them unformatted here.

        return new FilterTotals(
            $income,
            $expenses,
            $balance,
            $total_reconciled,
            $this->num_transactions
        );

//        return [
//            'income' => $income,
//            'expenses' => $expenses,
//            'balance' => number_format($balance, 2),
//            'reconciled' => number_format($total_reconciled, 2),
//            'num_transactions' => $this->num_transactions
//        ];
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
     * @param $filters
     * @return mixed
     */
    private function finishTransactionsQuery($query, $filters)
    {
        return $query->orderBy('date', 'desc')
                     ->orderBy('id', 'desc')
                     ->skip($filters['offset'])
                     ->take($filters['num_to_fetch'])
                     ->with('budgets')
                     ->with('account')
                     ->get();
    }

    /**
     *
     * @param $transactions
     * @return mixed
     */
    private function getFilteredTransactions(Collection $transactions)
    {
        foreach ($transactions as $transaction) {
            $date = [
                'user' => convertDate(Carbon::createFromFormat('Y-m-d', $transaction->date))
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
     * @param $query
     * @return mixed
     */
    public function countTransactions($query)
    {
        $query = clone $query;
        return $query->count();
    }

}