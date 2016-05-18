<?php

namespace App\Repositories\Filters;


use App\Models\Transaction;
use App\Traits\ForCurrentUserTrait;
use Carbon\Carbon;

/**
 * Class GraphsRepository
 * @package App\Repositories\Filters
 */
class GraphsRepository {

    use ForCurrentUserTrait;

    /**
     * @var FilterTotalsRepository
     */
    private $filterTotalsRepository;

    /**
     * @param FilterTotalsRepository $filterTotalsRepository
     */
    public function __construct(FilterTotalsRepository $filterTotalsRepository)
    {
        $this->filterTotalsRepository = $filterTotalsRepository;
    }

    /**
     *
     * @param $query
     * @param $queryForCalculatingBalance
     * @return array
     */
    public function getGraphTotals($query, $queryForCalculatingBalance)
    {
        if (Transaction::forCurrentUser()->count() < 1 || $query->count() < 1) {
            //User doesn't have any transactions
            return null;
        }

        //Todo: might need to check that there are actually transactions in the results for these variables to work?
        $minDate = Carbon::createFromFormat('Y-m-d', $query->min('date'))->startOfMonth();

        $maxDate = Carbon::createFromFormat('Y-m-d', $query->max('date'))->startOfMonth();

        $date = $maxDate;

        while ($minDate <= $date) {
            $monthTotals[] = $this->monthTotals($query, $date, $queryForCalculatingBalance);
            $date = $date->subMonths(1);

        }

        return [
            'monthTotals' => array_reverse($monthTotals),
            'maxTotal' => $this->getMax($monthTotals)
        ];
    }

    /**
     * For the graph for the months, getting the max value, either income or expense,
     * from the totals of the month, in order to calculate
     * the height of the bars
     */
    private function getMax($monthTotals)
    {
        //These worked before I did the formatting in FilterTotals.php
//        $maxIncome = max(collect($monthTotals)->lists('income'));
//        $maxExpenses = min(collect($monthTotals)->lists('expenses')) * -1;

//        $maxIncome = max($this->getRawValues($monthTotals, 'income'));

        $maxIncome = max(array_pluck($monthTotals, 'credit'));

        $maxExpenses = min(array_pluck($monthTotals, 'debit')) * -1;

        return max($maxIncome, $maxExpenses);
    }

    /**
     *
     * @param $query
     * @param Carbon $date
     * @param $queryForCalculatingBalance
     * @return \App\Models\Totals\FilterTotals
     */
    private function monthTotals($query, Carbon $date, $queryForCalculatingBalance)
    {
        $queryClone = clone $query;
        $queryClone = $queryClone
            ->whereMonth('date', '=', $date->month)
            ->whereYear('date', '=', $date->year);

        $queryForCalculatingBalanceClone = clone $queryForCalculatingBalance;
        $queryForCalculatingBalanceClone = $queryForCalculatingBalanceClone
            ->where('date', '<=', $date->copy()->endOfMonth()->format('Y-m-d'));

        if ($date->format('M Y') === 'Jan 2016') {

        }

        $monthTotals = $this->filterTotalsRepository->getFilterTotals($queryClone, $queryForCalculatingBalanceClone);

        $monthTotals->month = $date->format("M Y");
        return $monthTotals;

//        return [];


    }

}