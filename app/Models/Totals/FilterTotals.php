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
    public $creditIncludingTransfers;
    /**
     * @var
     */
    public $debitIncludingTransfers;
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
     * @var
     */
    public $credit;

    /**
     * @var
     */
    public $debit;

    /**
     * @param $credit
     * @param $debit
     * @param $creditIncludingTransfers
     * @param $debitIncludingTransfers
     * @param $balance
     * @param $reconciled
     * @param $numTransactions
     * @VP: (less important) Why this error when I try to return the FilterTotal object in the FilterController:
     * The Response content must be a string or object implementing __toString(), "object" given.
     * Update: So I added a toArray method. But why couldn't the FilterController return an object?
     */
    public function __construct($credit, $debit, $creditIncludingTransfers, $debitIncludingTransfers, $balance, $reconciled, $numTransactions)
    {
        $this->creditIncludingTransfers = $creditIncludingTransfers;
        $this->debitIncludingTransfers = $debitIncludingTransfers;
        $this->balance = $balance;
        $this->reconciled = $reconciled;
        $this->numTransactions = $numTransactions;
        $this->credit = $credit;
        $this->debit = $debit;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'credit' => $this->credit,
            'debit' => $this->debit,
            'creditIncludingTransfers' => $this->creditIncludingTransfers,
            'debitIncludingTransfers' => $this->debitIncludingTransfers,
            'balance' => $this->balance,
            'reconciled' => $this->reconciled,
            'numTransactions' => $this->numTransactions
        ];
    }
}