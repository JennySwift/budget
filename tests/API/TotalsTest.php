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
        return json_decode($response->getContent(), true);
    }

	/**
	 * A basic functional test example.
	 * @test
	 * @return void
	 */
	public function flex_budget_total_calculated_amount_equals_remaining_balance()
	{
        $user = $this->logInUser();
        $response = $this->getResponse();
        $totals = $this->getTotals($response);

        $this->assertEquals($totals['flexBudgetTotals']['allocatedPlusUnallocatedCalculatedAmount'], $totals['remainingBalance']);
		$this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
	}
}
