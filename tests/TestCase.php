<?php

use App\User;

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	protected $baseUrl = "http://localhost";

	protected $user;

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';

		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		return $app;
	}

	/**
	 * Make an API call
	 * @param $method
	 * @param $uri
	 * @param array $parameters
	 * @param array $cookies
	 * @param array $files
	 * @param array $server
	 * @param null $content
	 * @return \Illuminate\Http\Response
	 */
	public function apiCall($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
	{
		$headers = $this->transformHeadersToServerVars([
			'Accept' => 'application/json'
		]);
		$server = array_merge($server, $headers);

		return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
	}

	/**
	 *
	 * @return mixed
	 */
    public function logInUser($id = 1)
    {
        $user = User::find($id);
        $this->be($user);
        $this->user = $user;
    }

	/**
	 *
	 * @param $budget
	 */
	public function checkBudgetKeysExist($budget)
	{
		$this->assertArrayHasKey('id', $budget);
		$this->assertArrayHasKey('path', $budget);
		$this->assertArrayHasKey('name', $budget);
		$this->assertArrayHasKey('amount', $budget);
		$this->assertArrayHasKey('calculatedAmount', $budget);
		$this->assertArrayHasKey('type', $budget);
		$this->assertArrayHasKey('formattedStartingDate', $budget);
		$this->assertArrayHasKey('spent', $budget);
		$this->assertArrayHasKey('received', $budget);
		$this->assertArrayHasKey('spentAfterStartingDate', $budget);
		$this->assertArrayHasKey('spentBeforeStartingDate', $budget);
		$this->assertArrayHasKey('receivedAfterStartingDate', $budget);
		$this->assertArrayHasKey('cumulativeMonthNumber', $budget);
		$this->assertArrayHasKey('cumulative', $budget);
		$this->assertArrayHasKey('remaining', $budget);
		$this->assertArrayHasKey('transactionsCount', $budget);
	}

}
