<?php

namespace App\Models\Totals;

/**
 * Class FilterTotals
 * @package App\Totals
 */
class FilterTotals {

    /**
     * @var
     */
    public $income;
    /**
     * @var
     */
    public $expenses;
    /**
     * @var
     */
    public $balance;
    /**
     * @var
     */
    public $reconciled;
    /**
     * @var
     */
    public $numTransactions;

    /**
     * @param $income
     * @param $expenses
     * @param $balance
     * @param $reconciled
     * @param $numTransactions
     */
    public function __construct($income, $expenses, $balance, $reconciled, $numTransactions)
    {
        $this->income = $income;
        $this->expenses = $expenses;
        $this->balance = $balance;
        $this->reconciled = $reconciled;
        $this->numTransactions = $numTransactions;
    }
}