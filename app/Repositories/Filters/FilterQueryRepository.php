<?php

namespace App\Repositories\Filters;

use App\Models\Transaction;
use Auth;

class FilterQueryRepository
{
    /**
     * @var FilterBasicsRepository
     */
    private $filterBasicsRepository;
    /**
     * @var FilterBudgetsRepository
     */
    private $filterBudgetsRepository;
    /**
     * @var FilterNumBudgetsRepository
     */
    private $filterNumBudgetsRepository;

    /**
     * FilterQueryRepository constructor.
     * @param FilterBasicsRepository $filterBasicsRepository
     * @param FilterBudgetsRepository $filterBudgetsRepository
     * @param FilterNumBudgetsRepository $filterNumBudgetsRepository
     */
    public function __construct(FilterBasicsRepository $filterBasicsRepository, FilterBudgetsRepository $filterBudgetsRepository, FilterNumBudgetsRepository $filterNumBudgetsRepository)
    {

        $this->filterBasicsRepository = $filterBasicsRepository;
        $this->filterBudgetsRepository = $filterBudgetsRepository;
        $this->filterNumBudgetsRepository = $filterNumBudgetsRepository;
    }

    /**
     * $calculatingBalance is for ignoring singleDate and fromDate, so that the balance for the given time can be calculated
     * @param bool $calculatingBalance
     * @param array $filter
     * @return mixed
     */
    public function buildQuery(array $filter, $calculatingBalance = false)
    {
        // Prepare the query
        $query = Transaction::where('transactions.user_id', Auth::user()->id);

        // Apply filters to the transaction query
        foreach ($filter as $type => $value) {

            switch($type) {
                case "singleDate":
                case "fromDate":
                case "toDate":
                    $query = $this->filterBasicsRepository->filterDates($query, $type, $value, $calculatingBalance);
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
            }
        }

        return $query;
    }

    /**
     *
     * @param array $filter
     * @return mixed
     */
    public function buildQueryForCalculatingBalance(array $filter)
    {
        // Prepare the query
        $query = Transaction::where('transactions.user_id', Auth::user()->id);

        // Apply filters to the transaction query
        foreach ($filter as $type => $value) {

            switch($type) {
                case "singleDate":
                case "fromDate":
                case "toDate":
                    $query = $this->filterBasicsRepository->filterDates($query, $type, $value, true);
                    break;

                case "accounts":
                    $query = $this->filterBasicsRepository->filterAccounts($query, $value);
                    break;
            }
        }

        return $query;
    }

}