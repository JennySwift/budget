<?php

use App\Models\Transaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class TransactionsUpdateTest
 */
class TransactionsUpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_updates_a_transaction()
    {
        $this->logInUser();

        $transaction = Transaction::forCurrentUser()->first();

        $data = [
            'date' => '2015-10-01',
            'account_id' => 1,
            //Todo: make type updateable
//            'type' => 'expense',
            'description' => 'numbat',
            'merchant' => 'frog',
            'total' => 10.2,
            'reconciled' => 0,
            'allocated' => 0,
            'minutes' => '5',
            //Leave this empty because there was a bug once where the budgets for a transaction wouldn't empty
            'budgets' => []
        ];

        $response = $this->apiCall('PUT', '/api/transactions/'.$transaction->id, $data);
        $content = json_decode($response->getContent(), true)['data'];

        $this->checkTransactionKeysExist($content);

        //Check the transaction has the right data
        $this->assertEquals(1, $content['id']);
        $this->assertEquals('http://localhost/api/transactions/1', $content['path']);
        $this->assertEquals('2015-10-01', $content['date']);
        $this->assertEquals('01/10/15', $content['userDate']);
//        $this->assertEquals('expense', $content['type']);
        $this->assertEquals('numbat', $content['description']);
        $this->assertEquals('frog', $content['merchant']);
        $this->assertEquals(10.2, $content['total']);
        $this->assertEquals(0, $content['reconciled']);
        $this->assertEquals(0, $content['allocated']);
        $this->assertEquals(1, $content['account_id']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'bank account'
        ], $content['account']);

        $this->assertEquals([], $content['budgets']);
        $this->assertEquals(false, $content['multipleBudgets']);
        $this->assertEquals(5, $content['minutes']);

        //Check the status code
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Among other things, check the budgets are still there after reconciling
     * a transaction. (There was a bug that removed them when a transaction was
     * reconciled.)
     * @test
     * @return void
     */
    public function it_reconciles_a_transaction()
    {
        $this->logInUser();

        $transaction = Transaction::forCurrentUser()->find(13);

        $data = [
            'reconciled' => 1,
        ];

        $response = $this->apiCall('PUT', '/api/transactions/'.$transaction->id, $data);
        $content = json_decode($response->getContent(), true)['data'];

        $this->checkTransactionKeysExist($content);

        //Check the transaction has the right data
        $this->assertEquals(13, $content['id']);
        $this->assertEquals('http://localhost/api/transactions/13', $content['path']);
        $this->assertEquals('2015-09-01', $content['date']);
        $this->assertEquals('01/09/15', $content['userDate']);
        $this->assertEquals('income', $content['type']);
//        $this->assertEquals('numbat', $content['description']);
//        $this->assertEquals('frog', $content['merchant']);
        $this->assertEquals(200, $content['total']);
        $this->assertEquals(1, $content['reconciled']);
        $this->assertEquals(0, $content['allocated']);
        //Todo: This will error occasionally because account_id is random in the seeder
        $this->assertEquals(2, $content['account_id']);

        $this->assertEquals([
            'id' => 2,
            'name' => 'cash'
        ], $content['account']);

        $this->assertEquals(2, $content['budgets'][0]['id']);
        $this->assertCount(1, $content['budgets']);
        $this->assertEquals(false, $content['multipleBudgets']);
        $this->assertEquals(90, $content['minutes']);

        //Check the status code
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_the_budgets_for_a_transaction()
    {
        $this->logInUser();

        $transaction = Transaction::forCurrentUser()->first();

        $data = [
            'budgets' => [
                ['id' => 3],
                ['id' => 4]
            ]
        ];

        $response = $this->apiCall('PUT', '/api/transactions/'.$transaction->id, $data);
        $content = json_decode($response->getContent(), true)['data'];

        $this->checkTransactionKeysExist($content);

        $this->assertEquals(4, $content['budgets'][0]['id']);
        $this->assertEquals(3, $content['budgets'][1]['id']);

        //Check the status code
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_remove_a_transaction_description()
    {
        $this->logInUser();

        $transaction = Transaction::forCurrentUser()->first();

        $data = [
            'description' => ''
        ];

        $response = $this->apiCall('PUT', '/api/transactions/'.$transaction->id, $data);
        $content = json_decode($response->getContent(), true)['data'];

        $this->checkTransactionKeysExist($content);

        $this->assertEquals('', $content['description']);

        //Check the status code
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

}