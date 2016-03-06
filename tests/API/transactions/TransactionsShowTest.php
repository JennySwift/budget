<?php

use App\Models\Transaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class TransactionsShowTest
 */
class TransactionsShowTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_show_the_allocation_totals_for_a_transaction()
    {
        $this->logInUser();

        $transaction = Transaction::forCurrentUser()->where('allocated', 1)->first();

        $response = $this->call('GET', '/api/transactions/' . $transaction->id);
        $content = json_decode($response->getContent(), true);
//        dd($content);

//        $this->checktransactionKeysExist($content);
        $this->checkAllocationTotalKeysExist($content);

        $this->assertEquals('-', $content['fixedSum']);
        $this->assertEquals(100, $content['percentSum']);
        $this->assertEquals(-5, $content['calculatedAllocationSum']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

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