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
        $transactions = $this->getTransactions($query);

        $credit = 0;
        $debit = 0;
        $creditIncludingTransfers = 0;
        $debitIncludingTransfers = 0;
        $totalReconciled = 0;

        foreach ($transactions as $transaction) {
            switch($transaction->type) {
                case "income":
                    $credit += $transaction->total;
                    $creditIncludingTransfers += $transaction->total;
                    break;

                case "expense":
                    $debit += $transaction->total;
                    $debitIncludingTransfers += $transaction->total;
                    break;

                case "transfer":
                    if ($transaction->total > 0) {
                        $creditIncludingTransfers += $transaction->total;
                    }
                    elseif ($transaction->total < 0) {
                        $debitIncludingTransfers += $transaction->total;
                    }

                default:
                    // @TODO If nothing matches, throw an exception!!
            }

            if ($transaction->reconciled == 1) {
                $totalReconciled += $transaction->total;
            }
        }

        //todo: format these values again elsewhere. I need them unformatted here.

        return new FilterTotals(
            $credit,
            $debit,
            $creditIncludingTransfers,
            $debitIncludingTransfers,
            $creditIncludingTransfers + $debitIncludingTransfers,
            $totalReconciled,
            $this->countTransactions($query)
        );
    }

    /**
     *
     * @param $query
     * @return mixed
     */
    private function getTransactions($query)
    {
        return $query
            ->select('date', 'type', 'reconciled', 'total')
            ->orderBy('date', 'desc')
            ->get();
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