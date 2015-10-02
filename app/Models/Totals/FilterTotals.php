<?php

namespace App\Models\Totals;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class FilterTotals
 * @package App\Totals
 */
class FilterTotals implements Arrayable {

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
     * @VP: Why this error when I try to return the FilterTotal object in the FilterController:
     * The Response content must be a string or object implementing __toString(), "object" given.
     * Update: So I added a toArray method. But why couldn't the FilterController return an object?
     */
    public function __construct($income, $expenses, $balance, $reconciled, $numTransactions)
    {
        $this->income = $income;
        $this->expenses = $expenses;
        $this->balance = $balance;
        $this->reconciled = $reconciled;
        $this->numTransactions = $numTransactions;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'income' => $this->income,
            'expenses' => $this->expenses,
            'balance' => $this->balance,
            'reconciled' => $this->reconciled,
            'numTransactions' => $this->numTransactions
        ];
    }
}