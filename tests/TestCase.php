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

	/**
	 *
	 * @param $transaction
	 */
	public function checkTransactionKeysExist($transaction)
	{
		$this->assertArrayHasKey('id', $transaction);
		$this->assertArrayHasKey('path', $transaction);
		$this->assertArrayHasKey('date', $transaction);
		$this->assertArrayHasKey('userDate', $transaction);
		$this->assertArrayHasKey('type', $transaction);
		$this->assertArrayHasKey('description', $transaction);
		$this->assertArrayHasKey('merchant', $transaction);
		$this->assertArrayHasKey('total', $transaction);
		$this->assertArrayHasKey('reconciled', $transaction);
		$this->assertArrayHasKey('allocated', $transaction);
		$this->assertArrayHasKey('account_id', $transaction);
		$this->assertArrayHasKey('account', $transaction);
		$this->assertArrayHasKey('budgets', $transaction);
		$this->assertArrayHasKey('multipleBudgets', $transaction);
		$this->assertArrayHasKey('minutes', $transaction);
	}

	/**
	 *
	 * @param $totals
	 */
	public function checkTotalsKeysExist($totals)
	{
		$this->assertArrayHasKey('amount', $totals);
		$this->assertArrayHasKey('remaining', $totals);
		$this->assertArrayHasKey('cumulative', $totals);
		$this->assertArrayHasKey('spentBeforeStartingDate', $totals);
		$this->assertArrayHasKey('spentAfterStartingDate', $totals);
		$this->assertArrayHasKey('receivedAfterStartingDate', $totals);
	}

	/**
	 *
	 * @param $totals
	 */
	public function checkFlexBudgetTotalsKeysExist($totals)
	{
		$this->assertArrayHasKey('allocatedAmount', $totals);
		$this->assertArrayHasKey('allocatedRemaining', $totals);
		$this->assertArrayHasKey('allocatedCalculatedAmount', $totals);
		$this->assertArrayHasKey('spentBeforeStartingDate', $totals);
		$this->assertArrayHasKey('spentAfterStartingDate', $totals);
		$this->assertArrayHasKey('receivedAfterStartingDate', $totals);
		$this->assertArrayHasKey('unallocatedAmount', $totals);
		$this->assertArrayHasKey('allocatedPlusUnallocatedAmount', $totals);
		$this->assertArrayHasKey('allocatedPlusUnallocatedCalculatedAmount', $totals);
		$this->assertArrayHasKey('unallocatedCalculatedAmount', $totals);
		$this->assertArrayHasKey('allocatedPlusUnallocatedRemaining', $totals);
		$this->assertArrayHasKey('unallocatedRemaining', $totals);
	}

	/**
	 *
	 * @param $preferences
	 */
	public function checkPreferencesKeysExist($preferences)
	{
		$this->assertArrayHasKey('clearFields', $preferences);
		$this->assertArrayHasKey('colors', $preferences);
	}

	/**
	 *
	 * @param $account
	 */
	public function checkAccountKeysExist($account)
	{
		$this->assertArrayHasKey('id', $account);
		$this->assertArrayHasKey('user_id', $account);
		$this->assertArrayHasKey('name', $account);
//		$this->assertArrayHasKey('path', $account);
	}

}
