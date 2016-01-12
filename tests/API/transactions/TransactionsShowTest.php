<?php

use App\Models\Transaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TransactionsShowTest
 */
class TransactionsShowTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_checks_total_of_expense_transaction_is_negative()
    {
        $this->logInUser();

        $transaction = Transaction::forCurrentUser()->whereType('expense')->first();
        $this->assertTrue($transaction->total < 0);
    }

}