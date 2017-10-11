<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class FixedBudgetTotalsTest
 */
class FixedBudgetTotalsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_checks_the_fixed_budget_totals_are_correct()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', 'api/totals/fixedBudget');
        $totals = json_decode($response->getContent(), true);

        $this->checkTotalsKeysExist($totals);

        // Check if the values are correct according to our seeders!!
        $this->assertEquals(200, $totals['amount']);
        $this->assertEquals(1800, $totals['cumulative']);
        $this->assertEquals(-30, $totals['spentBeforeStartingDate']);
        $this->assertEquals(-40, $totals['spentOnOrAfterStartingDate']);
        $this->assertEquals(200, $totals['receivedOnOrAfterStartingDate']);
        $this->assertEquals(1960, $totals['remaining']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

}