<?php

use App\Models\FavouriteTransaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class FavouriteTransactionsDestroyTest
 */
class FavouriteTransactionsDestroyTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * @test
     * @return void
     */
    public function it_can_delete_a_favourite_transaction()
    {
        DB::beginTransaction();
        $this->logInUser();

        $favourite = FavouriteTransaction::first();

        $response = $this->call('DELETE', '/api/favouriteTransactions/'.$favourite->id);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->call('DELETE', '/api/favouriteTransactions/' . $favourite->id);
        $this->assertEquals(404, $response->getStatusCode());

        DB::rollBack();
    }
}