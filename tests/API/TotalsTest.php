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
    protected $user;

    /**
     * @var
     */
    protected $totals;

    /**
     * @var
     */
    protected $remainingBalance;

    /**
     * @var
     */
    protected $response;

    /**
     * @var
     */
    protected $fixedBudgetTotals;

    /**
     * @var
     */
    protected $flexBudgetTotals;

    /**
     *
     */
    private function setProperties()
    {
        $this->user = $this->logInUser();
        $this->response = $this->getResponse();
        $this->totals = $this->getTotals($this->response);
        $this->remainingBalance = $this->totals['remainingBalance'];
        $this->fixedBudgetTotals = $this->totals['fixedBudgetTotals'];
        $this->flexBudgetTotals = $this->totals['flexBudgetTotals'];
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
    private function getTotals()
    {
        return json_decode($this->response->getContent(), true);
    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function remaining_balance_is_sum_of_figures()
    {
        $this->setProperties();

        $sum = $this->totals['basicTotals']['credit']
            - $this->fixedBudgetTotals['remaining']
            + $this->totals['basicTotals']['EWB']
            + $this->flexBudgetTotals['spentBeforeStartingDate']
            + $this->fixedBudgetTotals['spentBeforeStartingDate']
            + $this->fixedBudgetTotals['spentAfterStartingDate']
            - $this->totals['basicTotals']['savings'];

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
        $this->setProperties();

        $sum = 0;
        foreach ($this->fixedBudgetTotals['budget'] as $budget) {
            $sum+= $budget['amount'];
        }

        $this->assertEquals($sum, $this->fixedBudgetTotals['amount']);
        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

	/**
	 * A basic functional test example.
	 * @test
	 * @return void
	 */
	public function flex_budget_total_calculated_amount_equals_remaining_balance()
	{
        $this->setProperties();

        $this->assertEquals($this->remainingBalance, $this->flexBudgetTotals['allocatedPlusUnallocatedCalculatedAmount']);
		$this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
	}
}
