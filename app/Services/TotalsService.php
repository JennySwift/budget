<?php

namespace App\Services;


use App\Models\Savings;
use App\Models\Total;
use App\Models\Transaction;
use Auth;
use DB;

/**
 * Class TotalsService
 * For dealing with the basic totals
 * @package App\Services
 */
class TotalsService {

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

    /**
     *
     * @return array
     */
    public function getBasicTotals()
    {
        $credit = $this->getCredit();
        $debit = $this->getDebit();

        $totals = array(
            "credit" => number_format($credit, 2),
            "debit" => number_format($debit, 2),
            "balance" => number_format($credit + $debit, 2),
            "reconciled_sum" => number_format($this->getReconciledSum(), 2),
            "savings" => number_format(Savings::getSavingsTotal(), 2),
            "EWB" => number_format($this->getEWB(), 2)
        );

        return $totals;
    }

    /**
     *
     * @return array
     */
    public function getBasicAndBudgetTotals() {
        $total = new Total();
        return [
            'basic' => $this->getBasicTotals(),
            'budget' => $total->getFixedAndFlexData()
        ];
    }
}