<?php

use App\Models\Budget;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TotalsTest extends TestCase {

    use DatabaseTransactions;

	/**
	 * A basic functional test example.
	 * @test
	 * @return void
	 */
	public function flex_budget_total_calculated_amount_equals_remaining_balance()
	{
//        $user = User::first();
//		$this->be($user);

//        $budget = [
//            'type' => 'fixed',
//            'name' => 'surf',
//            'amount' => 1000,
//            'starting_date' => '2015-01-01'
//        ];
//
//		$response = $this->apiCall('POST', '/api/budgets', $budget);
//        $content = json_decode($response->getContent(), true);
//
//		$this->assertEquals(201, $response->getStatusCode());
//        $this->assertArrayHasKey('type', $content);
//        $this->assertArrayHasKey('name', $content);
//        $this->assertArrayHasKey('amount', $content);
//        $this->assertArrayHasKey('starting_date', $content);
//        $this->assertEquals('fixed', $content['type']);
//        $this->assertEquals('surf', $content['name']);
//        $this->assertEquals(1000, $content['amount']);
//        $this->assertTrue(is_array($content['starting_date']));
//        $this->assertArrayHasKey('date', $content['starting_date']);
	}
}
