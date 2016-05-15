<?php

use App\Models\FavouriteTransaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class FavouriteTransactionsUpdateTest
 */
class FavouriteTransactionsUpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_update_a_favourite_transaction()
    {
        DB::beginTransaction();
        $this->logInUser();

        $favourite = FavouriteTransaction::forCurrentUser()->first();

        $response = $this->call('PUT', '/api/favouriteTransactions/'.$favourite->id, [
            'name' => 'koala',
            'type' => 'expense',
            'description' => 'kangaroo',
            'merchant' => 'wombat',
            'total' => 5,
            'account_id' => 2,
            'budget_ids' => [2,3]
        ]);
        $content = json_decode($response->getContent(), true);
        //dd($content);

        $this->checkfavouriteTransactionKeysExist($content);

        $this->assertEquals('koala', $content['name']);
        $this->assertEquals('expense', $content['type']);
        $this->assertEquals('kangaroo', $content['description']);
        $this->assertEquals('wombat', $content['merchant']);
        $this->assertEquals('5', $content['total']);
        $this->assertEquals(2, $content['account']['id']);
        $this->assertEquals(2, $content['budgets'][0]['id']);
        $this->assertEquals(3, $content['budgets'][1]['id']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }
}