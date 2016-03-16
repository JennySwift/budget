<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

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
//        $this->logInUser();
//
//        //Create a transaction
//        $transaction = [
//            'date' => '2015-01-01',
//            'account_id' => 1,
//            'type' => 'expense',
//            'description' => 'valid allocation test',
//            'merchant' => 'some store',
//            'total' => 82.04,
//            'reconciled' => 0,
//            'allocated' => 0,
//            'budgets' => [
//                ['id' => 2, 'name' => 'business'],
//                ['id' => 3, 'name' => 'groceries']
//            ]
//        ];
//
//        $response = $this->apiCall('POST', '/api/transactions', $transaction);
//        $content = json_decode($response->getContent(), true);
////        dd($content);
//        //Check the allocation
//
//        $response = $this->call('GET', '/api/transactions/' . $transaction->id);
//        $content = json_decode($response->getContent(), true);
//        //dd($content);
//
//        $this->checktransactionKeysExist($content);
//
//        $this->assertEquals(1, $content['id']);
//
//        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }
}