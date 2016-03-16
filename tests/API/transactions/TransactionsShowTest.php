<?php

use App\Models\Transaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class TransactionsShowTest
 */
class TransactionsShowTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_show_the_allocation_totals_for_a_transaction()
    {
        $this->logInUser();

        $transaction = Transaction::forCurrentUser()->where('allocated', 1)->first();

        $response = $this->call('GET', '/api/transactions/' . $transaction->id);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkAllocationTotalKeysExist($content);

        $this->assertEquals('-', $content['fixedSum']);
        $this->assertEquals(100, $content['percentSum']);
        $this->assertEquals(-5, $content['calculatedAllocationSum']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    /**
     * @test
     */
    public function it_checks_total_of_expense_transaction_is_negative()
    {
        $this->logInUser();

        $transaction = Transaction::forCurrentUser()->whereType('expense')->first();
        $this->assertTrue($transaction->total < 0);
    }

    /**
     * @test
     */
    public function it_can_calculate_if_the_budget_allocations_for_the_transaction_match_the_total_of_the_transaction()
    {
        $this->logInUser();

        //Find a transaction with multiple budgets
        $transaction = Transaction::forCurrentUser()
                ->has('assignedBudgets', '>', 1)
                ->first();

        $this->assertEquals(1, $transaction->validAllocation);

        //Check the data is as expected before adding a budget
        $this->assertEquals(2, $transaction->budgets[0]->id);
        $this->assertEquals(3, $transaction->budgets[1]->id);

        //Add a budget to the transaction. This should make the budget allocations for transaction not equal the transaction total
        //so the transaction should now be unallocated
        $response = $this->call('PUT', '/api/transactions/'.$transaction->id, [
            'addingBudgets' => true,
            'budget_ids' => [4]
        ]);
        $content = json_decode($response->getContent(), true);

//        dd($content);

        $this->checktransactionKeysExist($content);

        $this->assertEquals(0, $content['validAllocation']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

}