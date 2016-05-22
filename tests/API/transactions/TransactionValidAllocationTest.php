<?php

use App\Models\Budget;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class TransactionValidAllocationTest
 */
class TransactionValidAllocationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_determine_if_the_allocation_for_a_transaction_is_valid()
    {
        $this->logInUser();

        //Create a transaction
        $transaction = [
            'date' => '2015-01-01',
            'account_id' => 1,
            'type' => 'expense',
            'description' => 'valid allocation test',
            'merchant' => 'some store',
            'total' => 82.04,
            'reconciled' => 0,
            'allocated' => 0,
            'budget_ids' => [2,3]
        ];

        $response = $this->apiCall('POST', '/api/transactions', $transaction);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $updatedTransaction = $this->updateAllocation($content);
        $this->assertTrue($updatedTransaction['validAllocation']);
        $this->checkAllocationTotals($content);

    }

    /**
     * Update the allocation for the transaction's budgets
     * @param $content
     * @return mixed
     */
    private function updateAllocation($content)
    {
        $budget = Budget::find(2);
        $response = $this->call('PUT', '/api/budgets/' . $budget->id . '/transactions/'.$content['id'], [
            'type' => 'fixed',
            'value' => 52.05
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $budget = Budget::find(3);
        $response = $this->call('PUT', '/api/budgets/' . $budget->id . '/transactions/'.$content['id'], [
            'type' => 'fixed',
            'value' => 29.99
        ]);
        $updatedTransaction = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        return $updatedTransaction;
    }

    /**
     * @param $content
     */
    private function checkAllocationTotals($content)
    {
        $response = $this->call('GET', '/api/transactions/' . $content['id']);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertEquals(-82.04, $content['fixedSum']);
        $this->assertEquals(0, $content['percentSum']);
        $this->assertEquals(-82.04, $content['calculatedAllocationSum']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}