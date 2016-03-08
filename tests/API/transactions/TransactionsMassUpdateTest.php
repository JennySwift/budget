<?php use App\Models\Transaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TransactionsMassUpdateTest
 */
class TransactionsMassUpdateTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * @test
     * @return void
     */
    public function it_can_add_a_budget_to_a_transaction()
    {
//        DB::beginTransaction();
//        $this->logInUser();
//
//        //Find a transaction with a budged_id of one, and no other budgets
//        $transaction = Transaction::forCurrentUser()->whereHas('budgets', function ($q)
//        {
//            $q->where('budgets.id', 1);
//        })
//            ->has('budgets', '<', 2)
//            ->first();
//
//        $response = $this->call('PUT', '/api/transactions/'.$transaction->id, [
//            'addingBudgets' => true,
//            'budget_ids' => [2]
//        ]);
//        $content = json_decode($response->getContent(), true);
//        dd($content);
//
//        $this->checktransactionKeysExist($content);
//
//        $this->assertCount(2, $content['budgets']);
//        $this->assertEquals('numbat', $content['name']);
//
//        $this->assertEquals(200, $response->getStatusCode());
//
//        DB::rollBack();
    }
}