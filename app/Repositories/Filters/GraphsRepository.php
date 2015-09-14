<?php

namespace App\Repositories\Filters;


use Carbon\Carbon;

/**
 * Class GraphsRepository
 * @package App\Repositories\Filters
 */
class GraphsRepository {

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

    private function monthTotals($query, $date)
    {
        $queryClone = clone $query;
        $lastMonthTransactions = $queryClone
            ->whereMonth('date', '=', $date->month)
            ->whereYear('date', '=', $date->year)
            ->get();

        $monthTotals = $this->filterTotalsRepository->getFilterTotals($lastMonthTransactions, $query);
        $monthTotals->month = $date->format("M Y");

        return $monthTotals;
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

}