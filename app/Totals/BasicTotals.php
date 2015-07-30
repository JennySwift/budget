<?php namespace App\Totals;

use App\Models\Transaction;
use Auth;

/**
 * Class BasicTotals
 * @package App\Totals
 */
class BasicTotals {

    /**
     * Get the sum of all the user's expense transactions
     * @return int
     */
    public function getDebit()
    {
        $totals = Transaction::where('user_id', Auth::user()->id)
            ->where('type', 'expense')
            ->lists('total');

        $total_expense = 0;

        foreach ($totals as $total) {
            $total_expense += $total;
        }

        return $total_expense;
    }

    /**
     * Get the sum of all the user's income transactions
     * @return int
     */
    public function getCredit()
    {
        $totals = Transaction::where('user_id', Auth::user()->id)
            ->where('type', 'income')
            ->lists('total');

        $total_income = 0;

        foreach ($totals as $total) {
            $total_income += $total;
        }

        return $total_income;
    }

    /**
     * Get the sum of all the user's transactions that are reconciled
     * @return mixed
     */
    public function getReconciledSum()
    {
        $reconciled_sum = Transaction::where('user_id', Auth::user()->id)
            ->where('reconciled', 1)
            ->sum('total');

        return $reconciled_sum;
    }

    /**
     * Find all transactions that have no budget
     * and return the total of those transactions.
     * @return mixed
     */
    public function getEWB()
    {
        //Get all the ids of transactions that don't have a budget
        $ids = Transaction::where('user_id', Auth::user()->id)
            ->where('type', 'expense')
            ->has('tagsWithBudget', 0)
            ->lists('id');

        $total = Transaction::whereIn('transactions.id', $ids)
            ->sum('total');

        return $total;
    }
}