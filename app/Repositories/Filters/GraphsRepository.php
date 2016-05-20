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
        $transactions = $query
            ->select('date', 'type', 'total', 'reconciled')
            ->orderBy('date', 'desc')
            ->get();

        $transactionsFromBeginning = $queryForCalculatingBalance
            ->select('date', 'type', 'total', 'reconciled')
            ->orderBy('date', 'desc')
            ->get();

        if (count($transactions) < 1) {
            return null;
        }

        $minDate = Carbon::createFromFormat('Y-m-d', $transactions->min('date'))->startOfMonth();
        $maxDate = Carbon::createFromFormat('Y-m-d', $transactions->max('date'))->startOfMonth();

        $date = $maxDate;
        $totalsForAllMonths = [];

        while ($minDate <= $date) {
            $transactionsForMonth = $transactions->filter(function ($transaction) use ($date) {
                return Carbon::createFromFormat('Y-m-d', $transaction->date)->year === $date->year && $transactionMonth = Carbon::createFromFormat('Y-m-d', $transaction->date)->month === $date->month;
            });

            $transactionsFromBeginning = $transactionsFromBeginning->filter(function ($transaction) use ($date) {
                return Carbon::createFromFormat('Y-m-d', $transaction->date) <= $date->copy()->endOfMonth();
            });

            $totalsForMonth = $this->filterTotalsRepository->calculateFilterTotals($transactionsForMonth);
            $totalsForMonth['month'] = $date->format("M Y");

            $totalsFromBeginning = $this->filterTotalsRepository->calculateFilterTotals($transactionsFromBeginning);
            $totalsForMonth['balanceFromBeginning'] = $totalsFromBeginning['creditIncludingTransfers'] + $totalsFromBeginning['debitIncludingTransfers'];

            $totalsForAllMonths[] = $totalsForMonth;

            $date = $date->subMonths(1);
        }

        return [
            'monthTotals' => array_reverse($totalsForAllMonths)
        ];
    }
}