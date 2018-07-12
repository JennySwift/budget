<?php

use App\Models\Savings;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class TransactionsDestroyTest
 */
class TransactionsDestroyTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_deletes_a_transaction()
    {
        $this->logInUser();

        $transaction = Transaction::forCurrentUser()->first();

        $this->deleteTransaction($transaction);
    }

    /**
     * @test
     * @return void
     */
    public function it_decreases_the_savings_when_an_income_transaction_is_deleted()
    {
        $this->logInUser();

        $this->assertEquals('50.00', Savings::forCurrentUser()->first()->amount);

        $transaction = Transaction::forCurrentUser()
            ->where('type', 'income')
            ->first();

        $this->deleteTransaction($transaction);

        //Check the savings decreased
        $this->assertEquals('20.00', Savings::forCurrentUser()->first()->amount);
    }

    /**
     * @test
     * @return void
     */
    public function it_does_not_change_the_savings_when_an_expense_transaction_is_deleted()
    {
        $this->logInUser();

        $this->assertEquals('50.00', Savings::forCurrentUser()->first()->amount);

        $transaction = Transaction::forCurrentUser()
            ->where('type', 'expense')
            ->first();

        $this->deleteTransaction($transaction);

        //Check the savings decreased
        $this->assertEquals('50.00', Savings::forCurrentUser()->first()->amount);
    }

    /**
     *
     * @param $transaction
     */
    private function deleteTransaction($transaction)
    {
        $response = $this->apiCall('DELETE', '/api/transactions/' . $transaction->id);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->apiCall('DELETE', '/api/transactions/' . $transaction->id);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

}