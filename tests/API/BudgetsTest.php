<?php

use App\Models\Budget;
use App\User;
use Carbon\Carbon;
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
        $this->logInUser();

        $budget = [
            'type' => 'fixed',
            'name' => 'surf',
            'amount' => 1000,
            'starting_date' => '2015-01-01'
        ];

		$response = $this->apiCall('POST', '/api/budgets', $budget);
        $content = json_decode($response->getContent(), true)['data'];

		$this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('type', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('amount', $content);
        $this->assertArrayHasKey('formattedStartingDate', $content);
        $this->assertEquals('fixed', $content['type']);
        $this->assertEquals('surf', $content['name']);
        $this->assertEquals(1000, $content['amount']);
//        $this->assertTrue(is_array($content['starting_date']));
//        $this->assertArrayHasKey('date', $content['starting_date']);
	}

    /**
     * @TODO Test the validation for creating a budget
     */

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_updates_an_assigned_budget()
    {
        $this->logInUser();

        $budget = Budget::forCurrentUser()->where('type', '!=', 'unassigned')->first();

        if ($budget->type == 'fixed') {
            $type = 'flex';
        }
        else if ($budget->type == 'flex') {
            $type = 'fixed';
        }
        else if ($budget->type == 'unassigned') {
            $type = 'unassigned';
        }

        $response = $this->apiCall('PUT', '/api/budgets/'.$budget->id, [
//            'type' => ($budget->type == "fixed")?'flex':'fixed',
            'type' => $type,
            'name' => 'jetskiing',
            'amount' => 123,
            'starting_date' => '2016-01-01'
        ]);

        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($type, $content['type']);
        $this->assertEquals('jetskiing', $content['name']);
        $this->assertEquals('123', $content['amount']);
        $this->assertTrue(is_array($content['starting_date']));
        $this->assertArrayHasKey('date', $content['starting_date']);
        $date = Carbon::parse($content['starting_date']['date']);
        $this->assertEquals('2016-01-01', $date->format('Y-m-d'));
    }

    /**
     * @test
     * @return void
     */
    public function it_updates_an_unassigned_budget()
    {
        $this->logInUser();

        $budget = Budget::forCurrentUser()->where('type', 'unassigned')->first();

        $response = $this->apiCall('PUT', '/api/budgets/'.$budget->id, [
            'name' => 'bananas'
        ]);

        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('bananas', $content['name']);
    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_does_not_update_an_assigned_budget_if_values_are_the_same()
    {
        $this->logInUser();

        $budget = Budget::forCurrentUser()->where('type', '!=', 'unassigned')->first();

        $response = $this->apiCall('PUT', '/api/budgets/'.$budget->id, [
            'name' => $budget->name,
            'amount' => $budget->amount
        ]);

        $this->assertEquals(304, $response->getStatusCode());
        $this->seeInDatabase('budgets', [
            'user_id' => $this->user->id,
            'name' => $budget->name,
            'amount' => $budget->amount
        ]);
    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_deletes_a_budget()
    {
        $this->logInUser();

        $budget = Budget::forCurrentUser()->first();

        $response = $this->apiCall('DELETE', '/api/budgets/'.$budget->id);

        $this->assertEquals(204, $response->getStatusCode());
        $this->missingFromDatabase('budgets', [
            'user_id' => $this->user->id,
            'name' => $budget->name
        ]);
    }
}
