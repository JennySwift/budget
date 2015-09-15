<?php

use App\Models\Budget;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class TotalsTest
 */
class TotalsTest extends TestCase {

    use DatabaseTransactions;

    /**
     * @var
     */
    protected $remainingBalance;

    /**
     * @var
     */
    protected $fixedBudgetTotals;

    /**
     * @var
     */
    protected $flexBudgetTotals;

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function remaining_balance_is_sum_of_figures()
    {
        // I am logged in
        $this->logInUser();

        // I make a request for the totals
        $response = $this->getResponse();
        $totals = $this->getTotals($response);
        $this->setProperties($totals);

        // Set transactions and budgets amount to specific amount

        $sum = $totals->basicTotals->credit
            - $this->fixedBudgetTotals->remaining
            + $totals->basicTotals->EWB
            + $this->flexBudgetTotals->spentBeforeStartingDate
            + $this->fixedBudgetTotals->spentBeforeStartingDate
            + $this->fixedBudgetTotals->spentAfterStartingDate
            - $totals->basicTotals->savings;

        $this->assertEquals($sum, $this->remainingBalance);
        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function fixed_budget_amount_total_is_sum_of_amount_columns()
    {
        // I am logged in
        $this->logInUser();

        // I make a request for the totals
        $response = $this->getResponse();
        $totals = $this->getTotals($response);
        $this->setProperties($totals);

        $sum = 0;
        foreach ($this->fixedBudgetTotals->budget as $budget) {
            $sum+= $budget->amount;
        }

        $this->assertEquals($sum, $this->fixedBudgetTotals->amount);
        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    //Todo: Check all other total columns are correct, for each table
    //Todo: Check 'remaining' rows are correct

    /**
	 * A basic functional test example.
	 * @test
	 * @return void
	 */
	public function it_checks_if_flex_budget_total_calculated_amount_equals_remaining_balance()
	{
        // I am logged in
        $this->logInUser();

        // I make a request for the totals
        $response = $this->getResponse();
        $totals = $this->getTotals($response);
        $this->setProperties($totals);

        // And I assert that...
        $this->assertEquals($this->remainingBalance, $this->flexBudgetTotals->allocatedPlusUnallocatedCalculatedAmount);
		$this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
	}

    /**
     *
     */
    private function setProperties($totals)
    {
        $this->remainingBalance = $totals->remainingBalance;
        $this->fixedBudgetTotals = $totals->fixedBudgetTotals;
        $this->flexBudgetTotals = $totals->flexBudgetTotals;
    }

    /**
     * Get the totals response
     * @return Response
     */
    private function getResponse()
    {
        return $this->apiCall('GET', '/api/totals');
    }

    /**
     * Get the totals
     * @param $response
     * @return mixed
     */
    private function getTotals($response)
    {
        return json_decode($response->getContent(), false);
    }
}
