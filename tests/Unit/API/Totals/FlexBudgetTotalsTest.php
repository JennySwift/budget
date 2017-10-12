<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class FlexBudgetTotalsTest
 */
class FlexBudgetTotalsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_checks_the_flex_budget_totals_are_correct()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', 'api/totals/flexBudget');
        $totals = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
//        dd($totals);

        // Check if every attribute is present
        $this->checkFlexBudgetTotalsKeysExist($totals);

        // Check if the values are correct according to our seeders!!
        $this->assertEquals(20, $totals['allocatedAmount']);
        $this->assertEquals(1020, $totals['allocatedRemaining']);
        $this->assertEquals(40, $totals['allocatedCalculatedAmount']);
        $this->assertEquals(-15, $totals['spentBeforeStartingDate']);
        $this->assertEquals(-20, $totals['spentOnOrAfterStartingDate']);
        $this->assertEquals(1000, $totals['receivedOnOrAfterStartingDate']);

        $this->assertEquals(80, $totals['unallocatedAmount']);
        $this->assertEquals(100, $totals['allocatedPlusUnallocatedAmount']);
        $this->assertEquals(200, $totals['allocatedPlusUnallocatedCalculatedAmount']);
        $this->assertEquals(160, $totals['unallocatedCalculatedAmount']);
        $this->assertEquals(1180, $totals['allocatedPlusUnallocatedRemaining']);
        $this->assertEquals(160, $totals['unallocatedRemaining']);
    }

    /**
     * @test
     */
    public function it_checks_the_flex_budget_attributes_are_correct_when_including_the_extra_budget_attributes()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', 'api/budgets?flex=true&includeExtra=true');
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $budget = $content[0];
//        dd($budget);

        $this->checkBudgetKeysExist($budget, true);

        // Check if the values are correct according to our seeders!!
        $this->assertEquals("http://localhost/api/budgets/4", $budget['path']);
        $this->assertEquals("busking", $budget['name']);
        $this->assertEquals(10, $budget['amount']);
        $this->assertEquals(20, $budget['calculatedAmount']);
        $this->assertEquals('flex', $budget['type']);
        $this->assertEquals(Carbon::today()->subMonths(8)->format('d/m/y'), $budget['formattedStartingDate']);
        $this->assertEquals(-35, $budget['spent']);
        $this->assertEquals(1500, $budget['received']);
        $this->assertEquals(-15, $budget['spentBeforeStartingDate']);
        $this->assertEquals(-20, $budget['spentOnOrAfterStartingDate']);
        $this->assertEquals(1000, $budget['receivedOnOrAfterStartingDate']);
        $this->assertEquals(9, $budget['cumulativeMonthNumber']);
        $this->assertEquals(null, $budget['cumulative']);
        $this->assertEquals(1000, $budget['remaining']);
        $this->assertEquals(6, $budget['transactionsCount']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}