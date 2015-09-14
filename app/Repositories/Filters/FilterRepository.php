<?php namespace App\Repositories\Filters;

use App\Models\Transaction;
use Auth;
use Debugbar;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;

/**
 * Class FilterRepository
 * @package App\Repositories\Transactions
 */
class FilterRepository {

    /**
     * @var
     */
    private $filterBudgetsRepository;

    /**
     * @var GraphsRepository
     */
    private $graphsRepository;

    /**
     * @var FilterNumBudgetsRepository
     */
    private $filterNumBudgetsRepository;

    /**
     * @var FilterBasicsRepository
     */
    private $filterBasicsRepository;
    /**
     * @var FilterTotalsRepository
     */
    private $filterTotalsRepository;

    /**
     * @param FilterBasicsRepository $filterBasicsRepository
     * @param FilterBudgetsRepository $filterBudgetsRepository
     * @param GraphsRepository $graphsRepository
     * @param FilterNumBudgetsRepository $filterNumBudgetsRepository
     * @param FilterTotalsRepository $filterTotalsRepository
     */
    public function __construct(FilterBasicsRepository $filterBasicsRepository, FilterBudgetsRepository $filterBudgetsRepository, GraphsRepository $graphsRepository, FilterNumBudgetsRepository $filterNumBudgetsRepository, FilterTotalsRepository $filterTotalsRepository)
    {
        $this->filterBudgetsRepository = $filterBudgetsRepository;
        $this->graphsRepository = $graphsRepository;
        $this->filterNumBudgetsRepository = $filterNumBudgetsRepository;
        $this->filterBasicsRepository = $filterBasicsRepository;
        $this->filterTotalsRepository = $filterTotalsRepository;
        $this->defaults = Config::get('filters.defaults');
    }

    /**
     * @var
     */
    private $num_transactions;

    /**
     * @var
     */
    private $filters;

//    /**
//     * @var array
//     */
//    protected $defaults = [
//        "total" => [
//            "in" => "",
//            "out" => ""
//        ],
//        "types" => [
//            "in" => [],
//            "out" => []
//        ],
//        "accounts" => [
//            "in" => [],
//            "out" => []
//        ],
//        "single_date" => [
//            "in" => [
//                "user" => "",
//                "sql" => ""
//            ],
//            "out" => [
//                "user" => "",
//                "sql" => ""
//            ],
//        ],
//        "from_date" => [
//            "in" => [
//                "user" => "",
//                "sql" => ""
//            ],
//            "out" => [
//                "user" => "",
//                "sql" => ""
//            ],
//        ],
//        "to_date" => [
//            "in" => [
//                "user" => "",
//                "sql" => ""
//            ],
//            "out" => [
//                "user" => "",
//                "sql" => ""
//            ],
//        ],
//        "description" => [
//              "in" => "",
//              "out" => ""
//        ],
//        "merchant" => [
//              "in" => "",
//              "out" => ""
//        ],
//        "budgets" => [
//            "in" => [
//                "and" => [],
//                "or" => []
//            ],
//            "out" => []
//        ],
//        "numBudgets" => [
//            "in" => "all",
//            "out" => ""
//        ],
//        "reconciled" => "any",
//        "offset" => 0,
//        "num_to_fetch" => 30
//    ];

    /**
     * Filter the transactions
     * GET api/select/filter
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

            switch($type) {
                case "single_date":
                case "from_date":
                case "to_date":
                    $query = $this->filterBasicsRepository->filterDates($query, $type, $value);
                    break;
                case "accounts":
                    $query = $this->filterBasicsRepository->filterAccounts($query, $value);
                    break;
                case "types":
                    $query = $this->filterBasicsRepository->filterTypes($query, $value);
                    break;
                case "total":
                    $query = $this->filterBasicsRepository->filterTotal($query, $value);
                    break;
                case "reconciled":
                    $query = $this->filterBasicsRepository->filterReconciled($query, $value);
                    break;
                case "budgets":
                    $query = $this->filterBudgetsRepository->filterBudgets($query, $value);
                    break;
                case "numBudgets":
                    $query = $this->filterNumBudgetsRepository->filterNumBudgets($query, $value);
                    break;
                case "description":
                case "merchant":
                    $query = $this->filterBasicsRepository->filterDescriptionOrMerchant($query, $type, $value);
                    break;
                default:
                    // @TODO If nothing matches, throw an exception!!
            }
        }

        $this->num_transactions = $this->filterTotalsRepository->countTransactions($query);

        // If I didn't clone here, I couldn't reuse the original query because it would get modified
//        $transactionsQuery = clone $query;
//        $totalsQuery = clone $query;
//        $graphTotalsQuery = clone $query;

//        return $this->getGraphTotals($graphTotalsQuery);

        return [
            "transactions" => $this->getFilteredTransactions($this->finishTransactionsQuery($query, $this->filters)),
            "totals" => $this->filterTotalsRepository->getFilterTotals($this->finishTotalsQuery($query), $query),
            "graph_totals" => $this->graphsRepository->getGraphTotals($query)
        ];
    }

    /**
     * Get the totals after putting together the query
     * @param $query
     * @return mixed
     */
    private function finishTotalsQuery($query)
    {
        return $query
            ->select('date', 'type', 'reconciled', 'total')
            ->orderBy('date', 'desc')
            ->get();
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

}