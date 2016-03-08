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
    public $basicTotals;

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
     * @VP:
     * How do I pass a variable to this constructor without getting this error:
     * Unresolvable dependency resolving [Parameter #0 [ <required> array $filter ]] in class App\Models\Filter
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
     * @return array
     */
    public function setFilter(array $filters = [])
    {
        // Merge the argument with the defaults
        $this->filters = array_merge($this->defaults, $filters);

        $this->setQuery();
        $this->setNumTransactions();
        $this->setTransactions();
        $this->setBasicTotals();
        $this->setGraphTotals();
        return $this->toArray();
    }

    /**
     * Get the filtered transactions
     * @param array $filters
     * @return mixed
     */
    public function getTransactions(array $filters = [])
    {
        // Merge the argument with the defaults
        $this->filters = array_merge($this->defaults, $filters);

        $this->setQuery();
        $this->setTransactions();
        return $this->transactions;
    }

    /**
     * Get the basic filter totals
     * @param array $filters
     * @return mixed
     */
    public function getBasicTotals(array $filters = [])
    {
        // Merge the argument with the defaults
        $this->filters = array_merge($this->defaults, $filters);
        $this->setQuery();
        $this->setBasicTotals();
        return $this->basicTotals->toArray();
    }

    /**
     * Get the graph totals for the filtered transactions
     * @param array $filters
     * @return mixed
     */
    public function getGraphTotals(array $filters = [])
    {
        // Merge the argument with the defaults
        $this->filters = array_merge($this->defaults, $filters);
        $this->setQuery();
        $this->setGraphTotals();
        return $this->graphTotals;
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
                case "bugFix":
                    $query = $this->bugFix($query, $value);
                    break;

                case "singleDate":
                case "fromDate":
                case "toDate":
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
     * Just a temporary thing to fix where the default
     * allocation put 100% for tags without budgets
     * and 0% to tags with budgets
     * @VP:
     * Why isn't this working? It's returning transactions with budgets where
     * the calculated_allocation column on the pivot table is not 0.
     */
    private function bugFix($query, $value)
    {
        if ($value) {
            $query = $query->with(['budgets' => function($q)
            {
               $q->wherePivot('calculated_allocation', 0);
            }]);
        }

        return $query;
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
    private function setBasicTotals()
    {
        $this->basicTotals = $this->filterTotalsRepository->getFilterTotals($this->query);
    }

    /**
     *
     */
    private function setGraphTotals()
    {
        $this->graphTotals = $this->graphsRepository->getGraphTotals($this->query);
    }

    /**
     * Get the transactions after putting together the query
     * @return mixed
     */
    private function setTransactions()
    {
        $query = clone $this->query;

//        $transactions = $this->query->get();
//        $transactions = Transaction::forCurrentUser()
//            ->where(function($q)
//            {
//                $q->where('merchant', 'NOT LIKE', '%sally%')
//                ->orWhereNull('merchant');
//            })
//            ->get();
//        dd($transactions->toSql());
//        $transactions = Transaction::forCurrentUser()->get();
//        dd(count($transactions));
//        dd($transactions);

        $this->transactions = $query->orderBy('date', 'desc')
                     ->orderBy('id', 'desc')
                     ->skip($this->filters['offset'])
                     ->take($this->filters['numToFetch'])
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
            'totals' => $this->basicTotals,
            'graphTotals' => $this->graphTotals,
        ];
    }
}