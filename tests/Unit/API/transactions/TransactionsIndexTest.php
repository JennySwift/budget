<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class TransactionsIndexTest
 */
class TransactionsIndexTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * I'm not sure if this is still being used. I think the TransactionsController index method is for the autocompelte,
     * where as for getting the transactions it's /api/filter/transactions
     * @test
     * @return void
     */
    public function it_gets_the_transactions()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/transactions');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkTransactionKeysExist($content[0]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_autocompletes_the_transactions_by_description()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/transactions?column=description&typing=e');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkTransactionKeysExist($content[0]);

        foreach ($content as $transaction) {
            $this->assertContains('e', $transaction['description'], '', true);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_does_not_include_transfers_when_autocompleting_the_transactions_by_description()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/transactions?column=description&typing=');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkTransactionKeysExist($content[0]);

        foreach ($content as $transaction) {
            $this->assertNotEquals('transfer', $transaction['type']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_does_not_include_transfers_when_autocompleting_the_transactions_by_merchant()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/transactions?column=merchant&typing=');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkTransactionKeysExist($content[0]);

        foreach ($content as $transaction) {
            $this->assertNotEquals('transfer', $transaction['type']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_autocompletes_the_transactions_by_merchant()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/transactions?column=merchant&typing=e');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkTransactionKeysExist($content[0]);

        foreach ($content as $transaction) {
            $this->assertContains('e', $transaction['merchant'], '', true);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

}