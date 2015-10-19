<?php

use App\Models\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class TransactionsTest
 */
class TransactionsTest extends TestCase {

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

	/**
	 * @test
	 * @return void
	 */
	public function it_inserts_an_income_transaction()
	{
        $this->logInUser();

        $transaction = [
            'date' => ['sql' => '2015-01-01'],
            'account_id' => 1,
            'type' => 'income',
            'description' => 'interesting description',
            'merchant' => 'some store',
            'total' => 5,
            'reconciled' => 0,
            'allocated' => 0,
            'budgets' => [
                ['id' => 2, 'name' => 'business'],
                ['id' => 4, 'name' => 'busking']
            ]
        ];

		$response = $this->apiCall('POST', '/api/transactions', $transaction);
        $content = json_decode($response->getContent(), false);

		$this->assertEquals(201, $response->getStatusCode());
        $content = $content->data;

        $this->seeInDatabase('transactions', [
            'date' => '2015-01-01',
            'account_id' => 1,
            'type' => 'income',
            'description' => 'interesting description',
            'merchant' => 'some store',
            'total' => 5,
            'reconciled' => 0,
            'allocated' => 0
        ]);

        $this->seeInDatabase('budgets_transactions', [
            'transaction_id' => $content->id,
            'budget_id' => 2
        ]);

        $this->seeInDatabase('budgets_transactions', [
            'transaction_id' => $content->id,
            'budget_id' => 4
        ]);

        $this->assertObjectHasAttribute('date', $content);
        $this->assertObjectHasAttribute('account_id', $content);
        $this->assertObjectHasAttribute('type', $content);
        $this->assertObjectHasAttribute('description', $content);
        $this->assertObjectHasAttribute('merchant', $content);
        $this->assertObjectHasAttribute('total', $content);
        $this->assertObjectHasAttribute('reconciled', $content);
        $this->assertObjectHasAttribute('allocated', $content);

        $this->assertEquals('2015-01-01', $content->date);
        $this->assertEquals('1', $content->account_id);
        $this->assertEquals('income', $content->type);
        $this->assertEquals('interesting description', $content->description);
        $this->assertEquals('some store', $content->merchant);
        $this->assertEquals('5', $content->total);
        $this->assertEquals(0, $content->reconciled);
        $this->assertEquals(0, $content->allocated);
        $this->assertEquals('business', $content->budgets[0]->name);
        $this->assertEquals('busking', $content->budgets[1]->name);
	}

    /**
     * @TODO Test the validation for creating a budget
     */

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

        //Check all the keys are there
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('path', $content);
        $this->assertArrayHasKey('date', $content);
        $this->assertArrayHasKey('userDate', $content);
        $this->assertArrayHasKey('type', $content);
        $this->assertArrayHasKey('description', $content);
        $this->assertArrayHasKey('merchant', $content);
        $this->assertArrayHasKey('total', $content);
        $this->assertArrayHasKey('reconciled', $content);
        $this->assertArrayHasKey('allocated', $content);
        $this->assertArrayHasKey('account_id', $content);
        $this->assertArrayHasKey('account', $content);
        $this->assertArrayHasKey('budgets', $content);
        $this->assertArrayHasKey('multipleBudgets', $content);
        $this->assertArrayHasKey('minutes', $content);

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

        //Check all the keys are there
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('path', $content);
        $this->assertArrayHasKey('date', $content);
        $this->assertArrayHasKey('userDate', $content);
        $this->assertArrayHasKey('type', $content);
        $this->assertArrayHasKey('description', $content);
        $this->assertArrayHasKey('merchant', $content);
        $this->assertArrayHasKey('total', $content);
        $this->assertArrayHasKey('reconciled', $content);
        $this->assertArrayHasKey('allocated', $content);
        $this->assertArrayHasKey('account_id', $content);
        $this->assertArrayHasKey('account', $content);
        $this->assertArrayHasKey('budgets', $content);
        $this->assertArrayHasKey('multipleBudgets', $content);
        $this->assertArrayHasKey('minutes', $content);

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
        $this->assertEquals(2, $content['account_id']);

        $this->assertEquals([
            'id' => 2,
            'name' => 'cash'
        ], $content['account']);
//        dd($content['budgets']);

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

        $this->assertEquals(4, $content['budgets'][0]['id']);
        $this->assertEquals(3, $content['budgets'][1]['id']);

        //Check the status code
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_checks_the_description_for_a_transaction_can_be_removed()
    {
        $this->logInUser();

        $transaction = Transaction::forCurrentUser()->first();

        $data = [
            'description' => ''
        ];

        $response = $this->apiCall('PUT', '/api/transactions/'.$transaction->id, $data);

        $content = json_decode($response->getContent(), true)['data'];

        $this->assertEquals('', $content['description']);

        //Check the status code
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

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
