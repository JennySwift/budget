<?php

use App\User;
use App\Models\Savings;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SavingsTest extends TestCase {

    use DatabaseTransactions;

	/**
	 * A basic functional test example.
	 * @test
	 * @return void
	 */
	public function it_sets_the_savings_amount()
	{
        $user = $this->logInUser();

        $savings = Savings::forCurrentUser()->first();
        $this->seeInDatabase('savings', [
            'user_id' => $user->id,
            'amount' => $savings->amount
        ]);

		$response = $this->apiCall('PUT', '/api/savings/set', [
            'amount' => 100
        ]);

		$this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(100, $response->getContent());
	}

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_increases_the_savings_amount()
    {
        $user = $this->logInUser();

        $savings = Savings::forCurrentUser()->first();
        $this->seeInDatabase('savings', [
            'user_id' => $user->id,
            'amount' => $savings->amount
        ]);

        $response = $this->apiCall('PUT', '/api/savings/increase', [
            'amount' => 100
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($savings->amount + 100, $response->getContent());
    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_decreases_the_savings_amount()
    {
        $user = $this->logInUser();

        $savings = Savings::forCurrentUser()->first();
        $this->seeInDatabase('savings', [
            'user_id' => $user->id,
            'amount' => $savings->amount
        ]);

        // Correct request
        $response = $this->apiCall('PUT', '/api/savings/decrease', [
            'amount' => 10
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($savings->amount - 10, $response->getContent());
    }

    /**
     * @test
     */
    public function it_cannot_decrease_with_a_higher_amount()
    {
        $user = $this->logInUser();

        $savings = Savings::forCurrentUser()->first();
        $this->seeInDatabase('savings', [
            'user_id' => $user->id,
            'amount' => $savings->amount
        ]);

        // Bad request
        $response = $this->apiCall('PUT', '/api/savings/decrease', [
            'amount' => $savings->amount + 100
        ]);
        $this->assertEquals(400, $response->getStatusCode());
        $this->seeInDatabase('savings', [
            'user_id' => $user->id,
            'amount' => $savings->amount
        ]);
    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_cannot_set_a_negative_savings_amount()
    {
        $user = $this->logInUser();

        $savings = Savings::forCurrentUser()->first();
        $this->seeInDatabase('savings', [
            'user_id' => $user->id,
            'amount' => $savings->amount
        ]);

        $response = $this->apiCall('PUT', '/api/savings/set', [
            'amount' => -100
        ]);

        $this->assertEquals(422, $response->getStatusCode());
    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_cannot_set_a_string_as_savings_amount()
    {
        $user = $this->logInUser();

        $savings = Savings::forCurrentUser()->first();
        $this->seeInDatabase('savings', [
            'user_id' => $user->id,
            'amount' => $savings->amount
        ]);

        $response = $this->apiCall('PUT', '/api/savings/set', [
            'amount' => 'kangaroo'
        ]);

        $this->assertEquals(422, $response->getStatusCode());
    }
}
