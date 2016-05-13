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
     * @param $queryForCalculatingBalance
     * @return FilterTotals
     */
    public function getFilterTotals($query, $queryForCalculatingBalance = null)
    {
        $transactions = $this->getTransactions($query);
        $credit = 0;
        $debit = 0;
        $creditIncludingTransfers = 0;
        $debitIncludingTransfers = 0;
        $totalReconciled = 0;
        $balanceFromBeginning = null;

        if ($queryForCalculatingBalance) {
            $queryForCalculatingBalanceClone1 = clone $queryForCalculatingBalance;
            $queryForCalculatingBalanceClone2 = clone $queryForCalculatingBalance;
            $incomeFromBeginning = $queryForCalculatingBalanceClone1->where('type', 'income')->sum('total');
            $expensesFromBeginning = $queryForCalculatingBalanceClone2->where('type', 'expense')->sum('total');
            $balanceFromBeginning = $incomeFromBeginning + $expensesFromBeginning;
        }

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

        return new FilterTotals(
            $credit,
            $debit,
            $creditIncludingTransfers,
            $debitIncludingTransfers,
            $creditIncludingTransfers + $debitIncludingTransfers,
            $totalReconciled,
            $this->countTransactions($query),
            $balanceFromBeginning
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