<?php

use App\Models\FavouriteTransaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class FavouriteTransactionsIndexTest
 */
class FavouriteTransactionsIndexTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_favourite_transactions()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/favouriteTransactions');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkFavouriteTransactionKeysExist($content[0]);

        $this->assertEquals(200, $response->getStatusCode());
    }
    
}