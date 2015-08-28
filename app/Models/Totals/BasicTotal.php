<?php

namespace App\Models\Totals;

use App\Models\Savings;
use App\Models\Transaction;
use Auth;

/**
 * Class BasicTotal
 * @package App\Totals
 */
class BasicTotal {

    public $debit;
    public $credit;
    public $reconciledSum;
    public $EWB;
    public $savings;

    /**
     * Build this object from the database
     */
    static public function createFromDatabase()
    {
        $object = new static;
        $object->setDebit();
        $object->setCredit();
        $object->setReconciledSum();
        $object->setEWB();
        $object->setSavings();

        return $object;
    }

    /**
     * Get the sum of all the user's expense transactions
     * @return int
     */
    public function setDebit()
    {
        return Transaction::forCurrentUser()
                          ->where('type', 'expense')
                          ->sum('total');
    }

    /**
     * Get the sum of all the user's income transactions
     * @return int
     */
    public function setCredit()
    {
        return Transaction::forCurrentUser()
                          ->where('type', 'income')
                          ->sum('total');
    }

    /**
     * Get the sum of all the user's transactions that are reconciled
     * @return mixed
     */
    public function setReconciledSum()
    {
        $reconciled_sum = Transaction::where('user_id', Auth::user()->id)
            ->where('reconciled', 1)
            ->sum('total');

        $this->reconciledSum = $reconciled_sum;
    }

    /**
     * Find all transactions that have no budget
     * and return the total of those transactions.
     * @return mixed
     */
    public function setEWB()
    {
        //Get all the ids of transactions that don't have a budget
        $ids = Transaction::where('user_id', Auth::user()->id)
            ->where('type', 'expense')
            ->has('tagsWithBudget', 0)
            ->lists('id');

        $total = Transaction::whereIn('transactions.id', $ids)
            ->sum('total');

        $this->EWB = $total;
    }

    /**
     * Find savings total
     */
    public function setSavings()
    {
        $this->savings = Savings::getSavingsTotal();
    }

    /**
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            "credit" => number_format($this->credit, 2),
            "debit" => number_format($this->debit, 2),
            "balance" => number_format($this->credit + $this->debit, 2),
            "reconciled_sum" => number_format($this->reconciledSum, 2),
            "savings" => number_format($this->savings, 2),
            "EWB" => number_format($this->EWB, 2)
        );
    }

}