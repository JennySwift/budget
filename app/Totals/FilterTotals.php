<?php

namespace App\Totals;


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
        $this->income = new Format($income);
        $this->expenses = new Format($expenses);
        $this->balance = new Format($balance);
        $this->reconciled = new Format($reconciled);
        $this->numTransactions = $numTransactions;
    }
}