<?php

use App\Models\Budget;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BudgetsTest extends TestCase {

    use DatabaseTransactions;

	/**
	 * A basic functional test example.
	 * @test
	 * @return void
	 */
	public function it_creates_a_new_budget()
	{
        $user = User::first();
		$this->be($user);

        $budget = [
            'type' => 'fixed',
            'name' => 'surf',
            'amount' => 1000,
            'starting_date' => '2015-01-01'
        ];

		$response = $this->apiCall('POST', '/api/budgets', $budget);
        $content = json_decode($response->getContent(), true);

		$this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('type', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('amount', $content);
        $this->assertArrayHasKey('starting_date', $content);
        $this->assertEquals('fixed', $content['type']);
        $this->assertEquals('surf', $content['name']);
        $this->assertEquals(1000, $content['amount']);
        $this->assertTrue(is_array($content['starting_date']));
        $this->assertArrayHasKey('date', $content['starting_date']);
	}

    /**
     * @TODO Test the validation for creating a budget
     */

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_updates_a_budget()
    {
        $user = User::first();
        $this->be($user);

        $budget = Budget::forCurrentUser()->first();

        $response = $this->apiCall('PUT', '/api/budgets/'.$budget->id, [
            'name' => 'jetskiing'
        ]);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('budget', $content);
        $this->assertArrayHasKey('fixedBudgetTotals', $content);
        $this->assertArrayHasKey('flexBudgetTotals', $content);
        $this->assertArrayHasKey('basicTotals', $content);
        $this->assertArrayHasKey('remainingBalance', $content);
        $this->assertEquals('fixed', $content['type']);
        $this->assertEquals('surf', $content['name']);
        $this->assertEquals(1000, $content['amount']);
        $this->assertTrue(is_array($content['starting_date']));
        $this->assertArrayHasKey('date', $content['starting_date']);
    }
}
