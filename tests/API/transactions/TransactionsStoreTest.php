<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TransactionsStoreTest
 */
class TransactionsStoreTest extends TestCase
{
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
                ['id' => 2, 'name' => 'business'],
                ['id' => 4, 'name' => 'busking']
            ]
        ];

        $response = $this->apiCall('POST', '/api/transactions', $transaction);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $content = $content['data'];

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
            'transaction_id' => $content['id'],
            'budget_id' => 2
        ]);

        $this->seeInDatabase('budgets_transactions', [
            'transaction_id' => $content['id'],
            'budget_id' => 4
        ]);

        $this->checkTransactionKeysExist($content);

        $this->assertEquals('2015-01-01', $content['date']);
        $this->assertEquals('1', $content['account_id']);
        $this->assertEquals('income', $content['type']);
        $this->assertEquals('interesting description', $content['description']);
        $this->assertEquals('some store', $content['merchant']);
        $this->assertEquals('5', $content['total']);
        $this->assertEquals(0, $content['reconciled']);
        $this->assertEquals(0, $content['allocated']);
        $this->assertEquals('business', $content['budgets'][0]['name']);
        $this->assertEquals('busking', $content['budgets'][1]['name']);
    }

}