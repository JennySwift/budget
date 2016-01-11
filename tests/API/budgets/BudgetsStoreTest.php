<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class BudgetsStoreTest
 */
class BudgetsStoreTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @TODO Test the validation for creating a budget
     * @test
     * @return void
     */
    public function it_creates_a_new_budget()
    {
        $this->logInUser();

        $budget = [
            'type' => 'fixed',
            'name' => 'surf',
            'amount' => 1000,
            'starting_date' => '2015-01-01'
        ];

        $response = $this->apiCall('POST', '/api/budgets', $budget);
        $content = json_decode($response->getContent(), true)['data'];

        $this->checkBudgetKeysExist($content);

        $this->assertEquals('fixed', $content['type']);
        $this->assertEquals('surf', $content['name']);
        $this->assertEquals(1000, $content['amount']);
//        $this->assertTrue(is_array($content['starting_date']));
//        $this->assertArrayHasKey('date', $content['starting_date']);

        $this->assertEquals(201, $response->getStatusCode());
    }

}