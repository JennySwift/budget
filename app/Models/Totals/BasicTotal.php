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
    public $transactions;

    /**
     * BasicTotal constructor.
     */
    public function __construct($transactions = NULL)
    {
        $this->transactions = $transactions ? : Transaction::forCurrentUser()->get();
        $this->setDebit();
        $this->setCredit();
        $this->setReconciledSum();
        $this->setEWB();
        $this->setSavings();
    }

    /**
     * Get the sum of all the user's expense transactions
     * @return int
     */
    public function setDebit()
    {
        $debit = $this->transactions->filter(function($transaction) {
            return $transaction->type == 'expense';
        })->sum('total');

        $this->debit = $debit;
    }

    /**
     * Get the sum of all the user's income transactions
     * @return int
     */
    public function setCredit()
    {
        $credit = $this->transactions->filter(function($transaction) {
            return $transaction->type == 'income';
        })->sum('total');

        $this->credit = $credit;
    }

    /**
     * Get the sum of all the user's transactions that are reconciled
     * @return mixed
     */
    public function setReconciledSum()
    {
        $reconciledSum = $this->transactions->filter(function($transaction) {
            return $transaction->reconciled;
        })->sum('total');

        $this->reconciledSum = $reconciledSum;
    }

    /**
     * Find all transactions that have no budget
     * and return the total of those transactions.
     * @return mixed
     */
    public function setEWB()
    {
        //Get all the ids of transactions that don't have a budget
//        $ids = Transaction::forCurrentUser()
//                          ->where('type', 'expense')
//                          ->has('budgets', 0)
//                          ->get()
//                          ->sum('total');
//                          ->lists('id');

//        $total = Transaction::whereIn('transactions.id', $ids)
//                            ->sum('total');
        $total = $this->transactions->load('budgets')->filter(function($transaction) {
            return $transaction->type == 'expense' && !$transaction->budgets->count();
        })->sum('total');

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
            "credit" => $this->credit,
            "debit" => $this->debit,
            "balance" => $this->credit + $this->debit,
            "reconciledSum" => $this->reconciledSum,
            "savings" => $this->savings,
            "EWB" => $this->EWB
        );
    }

}