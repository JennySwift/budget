<?php

use App\Models\Savings;
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
        $content = json_decode($response->getContent(), true);

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
     * @test
     * @return void
     */
    public function it_can_change_a_transaction_type_from_expense_to_income_and_increases_the_savings()
    {
        $this->logInUser();

        $this->assertEquals('50.00', Savings::forCurrentUser()->first()->amount);

        $transaction = Transaction::forCurrentUser()
            ->where('type', 'expense')
            ->first();

        $data = [
            'type' => 'income',
//            'total' => 10.2,
        ];

        $response = $this->apiCall('PUT', '/api/transactions/'.$transaction->id, $data);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkTransactionKeysExist($content);

        //Check savings increased
        $this->assertEquals('55.00', Savings::forCurrentUser()->first()->amount);
        //Check the total is positive
        $this->assertEquals('50', $content['total']);

        //Check the status code
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_change_a_transaction_type_from_expense_to_income_and_increases_the_savings_and_changes_the_total()
    {
        $this->logInUser();

        $this->assertEquals('50.00', Savings::forCurrentUser()->first()->amount);

        $transaction = Transaction::forCurrentUser()
            ->where('type', 'expense')
            ->first();

        $data = [
            'type' => 'income',
            'total' => 500,
        ];

        $response = $this->apiCall('PUT', '/api/transactions/'.$transaction->id, $data);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkTransactionKeysExist($content);

        //Check savings increased
        $this->assertEquals('100.00', Savings::forCurrentUser()->first()->amount);
        //Check the total is positive
        $this->assertEquals('500', $content['total']);

        //Check the status code
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_change_a_transaction_type_from_income_to_expense_and_decreases_the_savings()
    {
        $this->logInUser();

        $this->assertEquals('50.00', Savings::forCurrentUser()->first()->amount);

        $transaction = Transaction::forCurrentUser()
            ->where('type', 'income')
            ->first();

        //Transaction total is 300

        $data = [
            'type' => 'expense',
//            'total' => 10.2,
        ];

        $response = $this->apiCall('PUT', '/api/transactions/'.$transaction->id, $data);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkTransactionKeysExist($content);

        //Check savings decreased
        $this->assertEquals('20.00', Savings::forCurrentUser()->first()->amount);
        //Check the total is positive
        $this->assertEquals('-300', $content['total']);

        //Check the status code
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_decreases_the_savings_if_an_income_transaction_total_is_decreased()
    {
        $this->logInUser();

        $this->assertEquals('50.00', Savings::forCurrentUser()->first()->amount);

        $transaction = Transaction::forCurrentUser()
            ->where('type', 'income')
            ->first();

        $data = [
            //decrease total from 300 to 200
            'total' => 200,
        ];

        $response = $this->apiCall('PUT', '/api/transactions/'.$transaction->id, $data);
        $content = json_decode($response->getContent(), true);

        $this->checkTransactionKeysExist($content);

        //Check the savings decreased
        $this->assertEquals('40.00', Savings::forCurrentUser()->first()->amount);

        //Check the status code
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_increases_the_savings_if_an_income_transaction_total_is_increased()
    {
        $this->logInUser();

        $this->assertEquals('50.00', Savings::forCurrentUser()->first()->amount);

        $transaction = Transaction::forCurrentUser()
            ->where('type', 'income')
            ->first();

        $data = [
            //decrease total from 300 to 400
            'total' => 400,
        ];

        $response = $this->apiCall('PUT', '/api/transactions/'.$transaction->id, $data);
        $content = json_decode($response->getContent(), true);

        $this->checkTransactionKeysExist($content);

        //Check the savings increased
        $this->assertEquals('60.00', Savings::forCurrentUser()->first()->amount);

        //Check the status code
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }


    /**
     * @test
     * @return void
     */
    public function it_does_not_change_the_savings_if_an_expense_transaction_total_is_decreased()
    {
        $this->logInUser();

        $this->assertEquals('50.00', Savings::forCurrentUser()->first()->amount);

        $transaction = Transaction::forCurrentUser()
            ->where('type', 'expense')
            ->first();

        $data = [
            //decrease total from -50 to -40
            'total' => -40,
        ];

        $response = $this->apiCall('PUT', '/api/transactions/'.$transaction->id, $data);
        $content = json_decode($response->getContent(), true);

        $this->checkTransactionKeysExist($content);

        //Check the savings did not change
        $this->assertEquals('50.00', Savings::forCurrentUser()->first()->amount);

        //Check the status code
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }


    /**
     * @test
     * @return void
     */
    public function it_does_not_change_the_savings_if_an_expense_transaction_total_is_increased()
    {
        $this->logInUser();

        $this->assertEquals('50.00', Savings::forCurrentUser()->first()->amount);

        $transaction = Transaction::forCurrentUser()
            ->where('type', 'expense')
            ->first();

        $data = [
            //increase total from -50 to -40
            'total' => -60,
        ];

        $response = $this->apiCall('PUT', '/api/transactions/'.$transaction->id, $data);
        $content = json_decode($response->getContent(), true);

        $this->checkTransactionKeysExist($content);

        //Check the savings did not change
        $this->assertEquals('50.00', Savings::forCurrentUser()->first()->amount);

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
        $content = json_decode($response->getContent(), true);

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
        $this->assertEquals(1, $content['account_id']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'bank account'
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
        $content = json_decode($response->getContent(), true);

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
        $content = json_decode($response->getContent(), true);

        $this->checkTransactionKeysExist($content);

        $this->assertEquals('', $content['description']);

        //Check the status code
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

}