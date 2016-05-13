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
        $balanceFromBeginning = null;

        if ($queryForCalculatingBalance) {
            $transactionsForCalculatingBalance = $this->getTransactions($queryForCalculatingBalance);
            $resultsForCalculatingBalance = $this->calculateFilterTotals($transactionsForCalculatingBalance);
            $balanceFromBeginning = $resultsForCalculatingBalance['creditIncludingTransfers'] + $resultsForCalculatingBalance['debitIncludingTransfers'];
        }

        $results = $this->calculateFilterTotals($transactions);

        return new FilterTotals(
            $results['credit'],
            $results['debit'],
            $results['creditIncludingTransfers'],
            $results['debitIncludingTransfers'],
            $results['creditIncludingTransfers'] + $results['debitIncludingTransfers'],
            $results['totalReconciled'],
            $this->countTransactions($query),
            $balanceFromBeginning,
            $results['positiveTransferTotal'],
            $results['negativeTransferTotal']
        );
    }

    /**
     *
     * @param $transactions
     * @return array
     */
    private function calculateFilterTotals($transactions)
    {
        $credit = 0;
        $debit = 0;
        $creditIncludingTransfers = 0;
        $debitIncludingTransfers = 0;
        $totalReconciled = 0;
        $positiveTransferTotal = 0;
        $negativeTransferTotal = 0;

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
                        $positiveTransferTotal += $transaction->total;
                    }
                    elseif ($transaction->total < 0) {
                        $debitIncludingTransfers += $transaction->total;
                        $negativeTransferTotal+= $transaction->total;
                    }
            }

            if ($transaction->reconciled == 1) {
                $totalReconciled += $transaction->total;
            }
        }

        return [
            'credit' => $credit,
            'debit' => $debit,
            'creditIncludingTransfers' => $creditIncludingTransfers,
            'debitIncludingTransfers' => $debitIncludingTransfers,
            'totalReconciled' => $totalReconciled,
            'positiveTransferTotal' => $positiveTransferTotal,
            'negativeTransferTotal' => $negativeTransferTotal,
        ];
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