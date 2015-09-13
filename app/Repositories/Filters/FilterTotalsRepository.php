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
     * @param $totals
     * @return array
     */
    public function getFilterTotals($totals, $query)
    {
        $income = 0;
        $expenses = 0;
        $total_reconciled = 0;

        foreach ($totals as $transaction) {
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

//        return [
//            'income' => $income,
//            'expenses' => $expenses,
//            'balance' => number_format($balance, 2),
//            'reconciled' => number_format($total_reconciled, 2),
//            'num_transactions' => $this->num_transactions
//        ];
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