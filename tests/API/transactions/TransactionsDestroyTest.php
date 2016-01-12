<?php

use App\Models\Transaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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

        $response = $this->apiCall('DELETE', '/api/transactions/'.$transaction->id);

        $this->assertEquals(204, $response->getStatusCode());
        $this->missingFromDatabase('transactions', [
            'user_id' => $this->user->id,
            'id' => $transaction->id
        ]);
    }

}