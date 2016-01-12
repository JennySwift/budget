<?php namespace App\Repositories\Savings;

use App\Models\Savings;
use App\Models\Transaction;

/**
 * Class SavingsRepository
 * @package App\Repositories\Savings
 */
class SavingsRepository
{
    /**
     * Before reversing the automatic insert into savings,
     * calculate the amount to subtract from savings.
     * This is for after an income transaction is deleted.
     * @param Transaction $transaction
     * @return float
     */
    public function calculateAmountToSubtract(Transaction $transaction)
    {
        // This value will change. Just for developing purposes.
        $percent = 10;
        return (float) $transaction->total / 100 * $percent;
    }

    /**
     * For when an income transaction total has been edited and decreased,
     * updating the savings accordingly (subtract percentage from savings)
     * @param $previous_total
     * @param $new_total
     * @return float
     */
    public static function calculateAfterDecrease($previous_total, $new_total)
    {
        $diff = $previous_total - $new_total;
        $percent = 10;
        return (float) $diff / 100 * $percent;
    }

    /**
     * For when an income transaction total has been edited and increased,
     * updating the savings accordingly (add percentage to savings)
     * @param $previous_total
     * @param $new_total
     * @return float
     */
    public static function calculateAfterIncrease($previous_total, $new_total)
    {
        $diff = $new_total - $previous_total;
        $percent = 10;
        return $diff / 100 * $percent;
    }

    /**
     * Add an amount into savings.
     * For when an income transaction is added.
     * @param $transaction
     * @return float
     */
    public static function calculateAfterIncomeAdded($transaction)
    {
        //$percent is planned to be configurable and stored in the database
        $percent = 10;
        $savings = Savings::forCurrentUser()->first();
        return $transaction->total / 100 * $percent;
    }

}