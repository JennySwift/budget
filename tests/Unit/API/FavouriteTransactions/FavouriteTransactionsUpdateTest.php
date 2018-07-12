<?php

use App\Models\FavouriteTransaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

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

        $this->checkFavouriteTransactionKeysExist($content);

        $this->assertEquals('koala', $content['name']);
        $this->assertEquals('expense', $content['type']);
        $this->assertEquals('kangaroo', $content['description']);
        $this->assertEquals('wombat', $content['merchant']);
        $this->assertEquals('5', $content['total']);
        $this->assertEquals(2, $content['account']['id']);
        $this->assertArrayNotHasKey('fromAccount', $content);
        $this->assertArrayNotHasKey('toAccount', $content);
        $this->assertEquals(2, $content['budgets'][0]['id']);
        $this->assertEquals(3, $content['budgets'][1]['id']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * Todo: I could also test switching from type transfer to another type, that the fromAccount and toAccount keys are removed.
     * @test
     * @return void
     */
    public function it_can_update_a_favourite_transfer_transaction()
    {
        DB::beginTransaction();
        $this->logInUser();

        $favourite = FavouriteTransaction::forCurrentUser()->first();

        $response = $this->call('PUT', '/api/favouriteTransactions/'.$favourite->id, [
            'name' => 'koala',
            'type' => 'transfer',
            'description' => 'kangaroo',
            'merchant' => 'wombat',
            'total' => 5,
            'from_account_id' => 2,
            'to_account_id' => 1,
            'budget_ids' => [2,3]
        ]);
        $content = json_decode($response->getContent(), true);
        //dd($content);

        $this->checkFavouriteTransactionKeysExist($content);

        $this->assertEquals('koala', $content['name']);
        $this->assertEquals('transfer', $content['type']);
        $this->assertEquals('kangaroo', $content['description']);
        $this->assertEquals('wombat', $content['merchant']);
        $this->assertEquals('5', $content['total']);
        $this->assertArrayNotHasKey('account', $content);
        $this->assertEquals(2, $content['fromAccount']['id']);
        $this->assertEquals(1, $content['toAccount']['id']);
        $this->assertEquals(2, $content['budgets'][0]['id']);
        $this->assertEquals(3, $content['budgets'][1]['id']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }
}