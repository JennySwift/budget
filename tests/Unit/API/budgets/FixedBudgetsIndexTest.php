<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class FixedBudgetsIndexTest
 */
class FixedBudgetsIndexTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_fixed_budgets()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/budgets?fixed=true');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkBudgetKeysExist($content[0]);

        foreach ($content as $budget) {
            $this->assertEquals('fixed', $budget['type']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_checks_the_fixed_budget_attributes_are_correct_when_including_the_extra_budget_attributes()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', 'api/budgets?fixed=true&includeExtra=true');
        $content = json_decode($response->getContent(), true);
        $budget = $content[0];

        $this->checkBudgetKeysExist($budget, true);

        // Check if the values are correct according to our seeders!!
        $this->assertEquals("http://localhost/api/budgets/2", $budget['path']);
        $this->assertEquals("business", $budget['name']);
        $this->assertEquals(100, $budget['amount']);
        $this->assertEquals(null, $budget['calculatedAmount']);
        $this->assertEquals('fixed', $budget['type']);
        $this->assertEquals(Carbon::today()->subMonths(8)->format('d/m/y'), $budget['formattedStartingDate']);
        $this->assertEquals(-70, $budget['spent']);
        $this->assertEquals(300, $budget['received']);
        $this->assertEquals(-40, $budget['spentOnOrAfterStartingDate']);
        $this->assertEquals(-30, $budget['spentBeforeStartingDate']);
        $this->assertEquals(200, $budget['receivedOnOrAfterStartingDate']);
        $this->assertEquals(9, $budget['cumulativeMonthNumber']);
        $this->assertEquals(900, $budget['cumulative']);
        $this->assertEquals(1060, $budget['remaining']);
        $this->assertEquals(6, $budget['transactionsCount']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_checks_the_fixed_budget_attributes_are_correct_when_expense_is_on_same_day_as_starting_date()
    {
        $this->logInUser();

        //Create expense and income transactions with budget id of 2
        $this->createTransactionsOnStartingDate();

        $response = $this->apiCall('GET', 'api/budgets?fixed=true&includeExtra=true');
        $content = json_decode($response->getContent(), true);
        $budget = $content[0];

        $this->checkBudgetKeysExist($budget, true);

        // Check if the values are correct according to our seeders!!
        $this->assertEquals("http://localhost/api/budgets/2", $budget['path']);
        $this->assertEquals("business", $budget['name']);
        $this->assertEquals(100, $budget['amount']);
        $this->assertEquals(null, $budget['calculatedAmount']);
        $this->assertEquals('fixed', $budget['type']);
        $this->assertEquals(Carbon::today()->subMonths(8)->format('d/m/y'), $budget['formattedStartingDate']);
        $this->assertEquals(-270, $budget['spent']);
        $this->assertEquals(305, $budget['received']);
        $this->assertEquals(-240, $budget['spentOnOrAfterStartingDate']);
        $this->assertEquals(-30, $budget['spentBeforeStartingDate']);
        $this->assertEquals(205, $budget['receivedOnOrAfterStartingDate']);
        $this->assertEquals(9, $budget['cumulativeMonthNumber']);
        $this->assertEquals(900, $budget['cumulative']);
        $this->assertEquals(865, $budget['remaining']);
        $this->assertEquals(8, $budget['transactionsCount']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Create an expense and income transaction with a budget id of 2
     * @return Response
     */
    private function createTransactionsOnStartingDate()
    {
        //Create expense transaction
        $transaction = [
            'date' => Config::get('budgets')['startingDate'],
            'account_id' => 1,
            'type' => 'expense',
            'total' => 200,
            'reconciled' => 0,
            'allocated' => 0,
            'budget_ids' => [2]
        ];

        $response = $this->call('POST', '/api/transactions', $transaction);

        //Create income transaction
        $transaction = [
            'date' => Config::get('budgets')['startingDate'],
            'account_id' => 1,
            'type' => 'income',
            'total' => 5,
            'reconciled' => 0,
            'allocated' => 0,
            'budget_ids' => [2]
        ];

        $response = $this->call('POST', '/api/transactions', $transaction);

        return $response;
    }

}