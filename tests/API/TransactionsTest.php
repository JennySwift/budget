<?php

use App\Models\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TransactionsTest
 */
class TransactionsTest extends TestCase {

    use DatabaseTransactions;

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
                ['id' => 3, 'name' => 'business'],
                ['id' => 4, 'name' => 'groceries']
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
            'budget_id' => 3
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
        $this->assertEquals('groceries', $content->budgets[1]->name);
	}

    /**
     * @TODO Test the validation for creating a budget
     */

    /**
     * //Todo
     * @test
     * @return void
     */
//    public function it_updates_a_transaction()
//    {
//        $this->logInUser();
//
//        $budget = Budget::forCurrentUser()->where('type', '!=', 'unassigned')->first();
//
//        if ($budget->type == 'fixed') {
//            $type = 'flex';
//        }
//        else if ($budget->type == 'flex') {
//            $type = 'fixed';
//        }
//        else if ($budget->type == 'unassigned') {
//            $type = 'unassigned';
//        }
//
//        $response = $this->apiCall('PUT', '/api/budgets/'.$budget->id, [
////            'type' => ($budget->type == "fixed")?'flex':'fixed',
//            'type' => $type,
//            'name' => 'jetskiing',
//            'amount' => 123,
//            'starting_date' => '2016-01-01'
//        ]);
//
//        $content = json_decode($response->getContent(), true);
//
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertEquals($type, $content['type']);
//        $this->assertEquals('jetskiing', $content['name']);
//        $this->assertEquals('123', $content['amount']);
//        $this->assertTrue(is_array($content['starting_date']));
//        $this->assertArrayHasKey('date', $content['starting_date']);
//        $date = Carbon::parse($content['starting_date']['date']);
//        $this->assertEquals('2016-01-01', $date->format('Y-m-d'));
//    }

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
