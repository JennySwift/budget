<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TransactionsIndexTest
 */
class TransactionsIndexTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_transactions()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/transactions');
        $content = json_decode($response->getContent(), true);
    //  dd($content);

        $this->checkTransactionKeysExist($content[0]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_favourite_transactions()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/favouriteTransactions');
        $content = json_decode($response->getContent(), true);
//          dd($content);

        $this->checkFavouriteTransactionKeysExist($content[0]);

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