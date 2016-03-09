<?php

use App\Models\Transaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class FilterInvalidAllocationTest
 */
class FilterInvalidAllocationTest extends FiltersTest
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_finds_the_transactions_with_invalid_allocation()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $this->makeSomeTransactionsHaveInvalidAllocation();

        $filter = [
            'invalidAllocation' => true
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

//        dd($this->transactions);

        $this->assertCount(2, $this->transactions);

        foreach ($this->transactions as $transaction) {
            $this->assertFalse($transaction['validAllocation']);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     *
     */
    private function makeSomeTransactionsHaveInvalidAllocation()
    {
        //Find a transaction with a budged_id of one, and no other budgets
        $transaction = Transaction::forCurrentUser()->whereHas('budgets', function ($q)
        {
            $q->where('budgets.id', 2);
        })
            ->has('budgets', '<', 2)
            ->first();

        $this->assertEquals(12, $transaction->id);

        $response = $this->call('PUT', '/api/transactions/'.$transaction->id, [
            'addingBudgets' => true,
            'budget_ids' => [3]
        ]);

//        dd(count(Transaction::find(12)->budgets));

        //Find a transaction with a budged_id of one, and no other budgets
        $transaction = Transaction::forCurrentUser()->whereHas('budgets', function ($q)
        {
            $q->where('budgets.id', 3);
        })
            ->first();

        $this->assertEquals(5, $transaction->id);

        //Check the budgets for the transaction are as expected, so we know that adding a budget should make the allocation invalid
        $this->assertEquals('fixed', $transaction->budgets[0]->type);
        $this->assertEquals(2, $transaction->budgets[0]->id);
        $this->assertEquals('fixed', $transaction->budgets[1]->type);
        $this->assertEquals(3, $transaction->budgets[1]->id);
        $this->assertCount(2, $transaction->budgets);

        $response = $this->call('PUT', '/api/transactions/'.$transaction->id, [
            'addingBudgets' => true,
            'budget_ids' => [4]
        ]);
        $content = json_decode($response->getContent(), true);
//        dd(count(Transaction::find(5)->budgets));
    }
}