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
    private $numTransactions;

    /**
     * @var
     */
    private $filters;

    /**
     * @var
     */
    private $query;

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
     *
     * @param array $filters
     */
    public function setFilter(array $filters = [])
    {
        // Merge the argument with the defaults
        $this->filters = array_merge($this->defaults, $filters);

        $this->setQuery();
        $this->setNumTransactions();
        $this->setTransactions();
        $this->setTotals();
        $this->setGraphTotals();
//        return $this->graphTotals;
        return $this->toArray();
    }

    /**
     *
     */
    private function setQuery()
    {
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

        $this->query = $query;
    }

    /**
     *
     */
    private function setNumTransactions()
    {
        $this->numTransactions = $this->filterTotalsRepository->countTransactions($this->query);
    }

    /**
     *
     */
    private function setTotals()
    {
        $this->totals = $this->filterTotalsRepository->getFilterTotals($this->finishTotalsQuery($this->query), $this->query);
    }

    /**
     *
     */
    private function setGraphTotals()
    {
        $this->graphTotals = $this->graphsRepository->getGraphTotals($this->query);
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
     * @return mixed
     */
    private function setTransactions()
    {
        $this->transactions = $this->query->orderBy('date', 'desc')
                     ->orderBy('id', 'desc')
                     ->skip($this->filters['offset'])
                     ->take($this->filters['num_to_fetch'])
                     ->with('budgets')
                     ->with('account')
                     ->get();
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