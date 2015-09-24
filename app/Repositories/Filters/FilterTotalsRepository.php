<?php

namespace App\Repositories\Filters;


use App\Models\Totals\FilterTotals;

/**
 * Class FilterTotalsRepository
 * @package App\Repositories\Filters
 */
class FilterTotalsRepository {

    /**
     *
     * @param $query
     * @return FilterTotals
     */
    public function getFilterTotals($query)
    {
        $transactions = $query
            ->select('date', 'type', 'reconciled', 'total')
            ->orderBy('date', 'desc')
            ->get();

        $income = 0;
        $expenses = 0;
        $total_reconciled = 0;

        foreach ($transactions as $transaction) {
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
            $this->countTransactions($query)
        );
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