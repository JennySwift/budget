<?php use App\Models\Transaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class TransactionsMassUpdateTest
 */
class TransactionsMassUpdateTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * It can add one budget to a transaction
     * @test
     * @return void
     */
    public function it_can_add_a_budget_to_a_transaction()
    {
        DB::beginTransaction();
        $this->logInUser();

        //Find a transaction with a budged_id of one, and no other budgets
        $transaction = Transaction::forCurrentUser()->whereHas('budgets', function ($q)
        {
            $q->where('budgets.id', 1);
        })
            ->has('budgets', '<', 2)
            ->first();

        $response = $this->call('PUT', '/api/transactions/'.$transaction->id, [
            'addingBudgets' => true,
            'budget_ids' => [2]
        ]);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checktransactionKeysExist($content);

        $this->assertCount(2, $content['budgets']);
        $this->assertEquals(1, $content['budgets'][0]['id']);
        $this->assertEquals(2, $content['budgets'][1]['id']);

        //Check a couple of fields are the same because only the budgets should be changed
        $this->assertEquals($transaction->description, $content['description']);
        $this->assertEquals($transaction->merchant, $content['merchant']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * It can add many budgets to a transaction
     * @test
     * @return void
     */
    public function it_can_add_budgets_to_a_transaction()
    {
        DB::beginTransaction();
        $this->logInUser();

        //Find a transaction with a budged_id of one, and no other budgets
        $transaction = Transaction::forCurrentUser()->whereHas('budgets', function ($q)
        {
            $q->where('budgets.id', 1);
        })
            ->has('budgets', '<', 2)
            ->first();

        $response = $this->call('PUT', '/api/transactions/'.$transaction->id, [
            'addingBudgets' => true,
            'budget_ids' => [2,4]
        ]);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checktransactionKeysExist($content);

        $this->assertCount(3, $content['budgets']);
        $this->assertEquals(1, $content['budgets'][0]['id']);
        $this->assertEquals(2, $content['budgets'][1]['id']);
        $this->assertEquals(4, $content['budgets'][2]['id']);

        //Check a couple of fields are the same because only the budgets should be changed
        $this->assertEquals($transaction->description, $content['description']);
        $this->assertEquals($transaction->merchant, $content['merchant']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_cannot_add_a_budget_to_a_transaction_that_already_has_that_budget()
    {
        DB::beginTransaction();
        $this->logInUser();

        //Find a transaction with a budged_id of one, and no other budgets
        $transaction = Transaction::forCurrentUser()->whereHas('budgets', function ($q)
        {
            $q->where('budgets.id', 1);
        })
            ->has('budgets', '<', 2)
            ->first();

        $response = $this->call('PUT', '/api/transactions/'.$transaction->id, [
            'addingBudgets' => true,
            'budget_ids' => [1]
        ]);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checktransactionKeysExist($content);

        $this->assertCount(1, $content['budgets']);
        $this->assertEquals(1, $content['budgets'][0]['id']);

        //Check a couple of fields are the same because only the budgets should be changed
        $this->assertEquals($transaction->description, $content['description']);
        $this->assertEquals($transaction->merchant, $content['merchant']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }
}