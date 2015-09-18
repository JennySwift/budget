<?php namespace App\Models;

use App\Repositories\Filters\FilterBasicsRepository;
use App\Repositories\Filters\FilterBudgetsRepository;
use App\Repositories\Filters\FilterNumBudgetsRepository;
use App\Repositories\Filters\FilterTotalsRepository;
use App\Repositories\Filters\GraphsRepository;
use Auth;
use Debugbar;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Config;

/**
 * Class Filter
 * @property array filter
 * @package App\Repositories\Transactions
 */
class Filter implements Arrayable {

    /**
     * @var
     */
    public $transactions;

    /**
     * @var
     */
    public $graphTotals;

    /**
     * @var
     */
    public $totals;

    /**
     * @var FilterBudgetsRepository
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
     * @var
     */
    private $num_transactions;

    /**
     * @var
     */
    private $filters;

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

//        $this->setTransactions();
//        $this->setTotals();
//        $this->setGraphTotals();
    }

    /**
     *
     */
//    private function setTransactions()
//    {
//        $this->transactions = $transactions;
//    }

    /**
     *
     */
//    private function setTotals()
//    {
//        $this->totals= $totals;
//    }

    /**
     *
     */
//    private function setGraphTotals()
//    {
//        $this->graphTotals= $graphTotals;
//    }

    /**
     * Filter the transactions
     * GET api/filter
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
        $this->transactions = $this->finishTransactionsQuery($query, $this->filters);
        $this->totals = $this->filterTotalsRepository->getFilterTotals($this->finishTotalsQuery($query), $query);
        $this->graphTotals = $this->graphsRepository->getGraphTotals($query);

//        return $this;
        return [
            'transactions' => $this->transactions,
            'totals' => $this->totals,
            'graphTotals' => $this->graphTotals,
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
        $transactions = $query->orderBy('date', 'desc')
                     ->orderBy('id', 'desc')
                     ->skip($filters['offset'])
                     ->take($filters['num_to_fetch'])
                     ->with('budgets')
                     ->with('account')
                     ->get();

        return $transactions;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'transactions' => $this->transactions,
            'totals' => $this->totals,
            'graphTotals' => $this->graphTotals,

        ];
    }
}