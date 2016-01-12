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
     * @return array
     */
    public function getGraphTotals($query)
    {
        if (Transaction::forCurrentUser()->count() < 1) {
            //User doesn't have any transactions
            return false;
        }

        $minDate = Carbon::createFromFormat('Y-m-d', $query->min('date'))->startOfMonth();
        $maxDate = Carbon::createFromFormat('Y-m-d', $query->max('date'))->startOfMonth();

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

//        $maxIncome = max($this->getRawValues($monthsTotals, 'income'));

        $maxIncome = max(array_pluck($monthsTotals, 'credit'));

        $maxExpenses = min(array_pluck($monthsTotals, 'debit')) * -1;

        return max($maxIncome, $maxExpenses);
    }

    /**
     *
     * @param $query
     * @param $date
     * @return \App\Models\Totals\FilterTotals
     */
    private function monthTotals($query, $date)
    {
        $queryClone = clone $query;
        $queryClone = $queryClone
            ->whereMonth('date', '=', $date->month)
            ->whereYear('date', '=', $date->year);

        $monthTotals = $this->filterTotalsRepository->getFilterTotals($queryClone);
        $monthTotals->month = $date->format("M Y");

        return $monthTotals;
    }

}