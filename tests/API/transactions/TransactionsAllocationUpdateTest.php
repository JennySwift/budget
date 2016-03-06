<?php

use App\Models\Transaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TransactionsUpdateAllocationTest
 * 
 * Todo: check if the transaction only has two budgets,
 * that when the percent allocation on one is changed,
 * the other is changed so the total percent is 100
 */
class TransactionsUpdateAllocationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_update_the_allocation_for_a_transaction_with_a_fixed_amount()
    {
        DB::beginTransaction();
        $this->logInUser();

        //Find a transaction that has multiple budgets
        $transactions = Transaction::forCurrentUser()->get();
        foreach ($transactions as $t) {
            if ($t->multipleBudgets) {
                $transaction = $t;
            }
        }

        $budget = $transaction->budgets[0];

        $response = $this->call('PUT', '/api/transactions/'.$transaction->id, [
            'updatingAllocation' => true,
            'budget_id' => $budget->id,
            'type' => 'fixed',
            'value' => 89
        ]);
        $content = json_decode($response->getContent(), true);
//        dd($content);

//        $this->checkTransactionKeysExist($content);
        //Todo: budgets here haven't been transformed. I don't think the transformer has the allocation data I need though.
//        $this->checkBudgetKeysExist($content['budgets'][0]);
        $this->checkAllocationTotalKeysExist($content['totals']);


        $this->assertEquals(-89, $content['budgets'][0]['pivot']['allocated_fixed']);
        $this->assertNull($content['budgets'][0]['pivot']['allocated_percent']);
        $this->assertEquals(-89, $content['budgets'][0]['pivot']['calculated_allocation']);

        $this->assertEquals(-89, $content['totals']['fixedSum']);
        $this->assertEquals(0, $content['totals']['percentSum']);
        $this->assertEquals(-89, $content['totals']['calculatedAllocationSum']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_the_allocation_for_a_transaction_with_a_percentage_of_the_transaction()
    {
        DB::beginTransaction();
        $this->logInUser();

        //Find a transaction that has multiple budgets
        $transactions = Transaction::forCurrentUser()->get();
        foreach ($transactions as $t) {
            if ($t->multipleBudgets) {
                $transaction = $t;
            }
        }

        $budget = $transaction->budgets[0];

        //Check the figures are as expected before updating the allocation
        $this->assertEquals(-30, $transaction->total);
        $this->assertEquals(100, $budget->pivot->allocated_percent);

        $response = $this->call('PUT', '/api/transactions/'.$transaction->id, [
            'updatingAllocation' => true,
            'budget_id' => $budget->id,
            'type' => 'percent',
            'value' => 50
        ]);
        $content = json_decode($response->getContent(), true);
//        dd($content);

//        $this->checkTransactionKeysExist($content);
        //Todo: budgets here haven't been transformed. I don't think the transformer has the allocation data I need though.
//        $this->checkBudgetKeysExist($content['budgets'][0]);
        $this->checkAllocationTotalKeysExist($content['totals']);


        $this->assertNull($content['budgets'][0]['pivot']['allocated_fixed']);
        $this->assertEquals(50, $content['budgets'][0]['pivot']['allocated_percent']);
        $this->assertEquals(-15, $content['budgets'][0]['pivot']['calculated_allocation']);

        $this->assertEquals('-', $content['totals']['fixedSum']);
        $this->assertEquals(100, $content['totals']['percentSum']);
        $this->assertEquals(-30, $content['totals']['calculatedAllocationSum']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }
}