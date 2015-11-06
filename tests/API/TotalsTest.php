<?php

use App\Models\Budget;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class TotalsTest
 * Each month I will need to change the starting date of the budgets in the seeder
 * (config/budgets.php), and this line for both fixed and flex methods in this file:
 * $this->assertEquals("01/02/15", $budget->formattedStartingDate);
 * in order for the tests to pass.
 */
class TotalsTest extends TestCase {

    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_load_the_totals_for_the_sidebar()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', 'api/totals/sidebar');
        $content = json_decode($response->getContent(), false)->data;

        $this->assertResponseOk();

        // Check if every attribute is present
        $this->assertObjectHasAttribute('credit', $content);
        $this->assertObjectHasAttribute('debit', $content);
        $this->assertObjectHasAttribute('savings', $content);
        $this->assertObjectHasAttribute('balance', $content);
        $this->assertObjectHasAttribute('reconciledSum', $content);
        $this->assertObjectHasAttribute('expensesWithoutBudget', $content);
        $this->assertObjectHasAttribute('remainingBalance', $content);
        $this->assertObjectHasAttribute('remainingFixedBudget', $content);
        $this->assertObjectHasAttribute('cumulativeFixedBudget', $content);
        $this->assertObjectHasAttribute('expensesWithFixedBudgetAfterStartingDate', $content);
        $this->assertObjectHasAttribute('expensesWithFixedBudgetBeforeStartingDate', $content);
        $this->assertObjectHasAttribute('expensesWithFlexBudgetAfterStartingDate', $content);
        $this->assertObjectHasAttribute('expensesWithFlexBudgetBeforeStartingDate', $content);

        // Check if the values are correct according to our seeders!!
        $this->assertEquals(2350, $content->credit);
        $this->assertEquals(-160, $content->debit);
        $this->assertEquals(50, $content->savings);
        $this->assertEquals(2190, $content->balance);
        $this->assertEquals(1050, $content->reconciledSum);
        $this->assertEquals(-55, $content->expensesWithoutBudget);
        $this->assertEquals(200, $content->remainingBalance);
        $this->assertEquals(1960, $content->remainingFixedBudget);
        $this->assertEquals(1800, $content->cumulativeFixedBudget);
        $this->assertEquals(-40, $content->expensesWithFixedBudgetAfterStartingDate);
        $this->assertEquals(-30, $content->expensesWithFixedBudgetBeforeStartingDate);
        $this->assertEquals(-20, $content->expensesWithFlexBudgetAfterStartingDate);
        $this->assertEquals(-15, $content->expensesWithFlexBudgetBeforeStartingDate);
    }

    /**
     * @test
     */
    public function it_checks_the_fixed_budget_attributes_are_correct()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', 'api/fixedBudgets');
        $content = json_decode($response->getContent(), false)->data;

        $this->assertResponseOk();

        $budget = $content[0];

        // Check if every attribute is present
        $this->assertObjectHasAttribute('path', $budget);
        $this->assertObjectHasAttribute('name', $budget);
        $this->assertObjectHasAttribute('amount', $budget);
        $this->assertObjectHasAttribute('calculatedAmount', $budget);
        $this->assertObjectHasAttribute('type', $budget);
        $this->assertObjectHasAttribute('formattedStartingDate', $budget);
        $this->assertObjectHasAttribute('spent', $budget);
        $this->assertObjectHasAttribute('received', $budget);
        $this->assertObjectHasAttribute('spentAfterStartingDate', $budget);
        $this->assertObjectHasAttribute('spentBeforeStartingDate', $budget);
        $this->assertObjectHasAttribute('receivedAfterStartingDate', $budget);
        $this->assertObjectHasAttribute('cumulativeMonthNumber', $budget);
        $this->assertObjectHasAttribute('cumulative', $budget);
        $this->assertObjectHasAttribute('remaining', $budget);
        $this->assertObjectHasAttribute('transactionsCount', $budget);

        // Check if the values are correct according to our seeders!!
        $this->assertEquals("http://localhost/api/budgets/2", $budget->path);
        $this->assertEquals("business", $budget->name);
        $this->assertEquals(100, $budget->amount);
        $this->assertEquals(null, $budget->calculatedAmount);
        $this->assertEquals('fixed', $budget->type);
        $this->assertEquals("01/03/15", $budget->formattedStartingDate);
        $this->assertEquals(-70, $budget->spent);
        $this->assertEquals(300, $budget->received);
        $this->assertEquals(-40, $budget->spentAfterStartingDate);
        $this->assertEquals(-30, $budget->spentBeforeStartingDate);
        $this->assertEquals(200, $budget->receivedAfterStartingDate);
        $this->assertEquals(9, $budget->cumulativeMonthNumber);
        $this->assertEquals(900, $budget->cumulative);
        $this->assertEquals(1060, $budget->remaining);
        $this->assertEquals(6, $budget->transactionsCount);
    }

    /**
     * @test
     */
    public function it_checks_the_flex_budget_attributes_are_correct()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', 'api/flexBudgets');
        $content = json_decode($response->getContent(), false);

        $this->assertResponseOk();
        $budget = $content[0];
//        dd($budget);

        // Check if every attribute is present
        $this->assertObjectHasAttribute('path', $budget);
        $this->assertObjectHasAttribute('name', $budget);
        $this->assertObjectHasAttribute('amount', $budget);
        $this->assertObjectHasAttribute('calculatedAmount', $budget);
        $this->assertObjectHasAttribute('type', $budget);
        $this->assertObjectHasAttribute('formattedStartingDate', $budget);
        $this->assertObjectHasAttribute('spent', $budget);
        $this->assertObjectHasAttribute('received', $budget);
        $this->assertObjectHasAttribute('spentAfterStartingDate', $budget);
        $this->assertObjectHasAttribute('spentBeforeStartingDate', $budget);
        $this->assertObjectHasAttribute('receivedAfterStartingDate', $budget);
        $this->assertObjectHasAttribute('cumulativeMonthNumber', $budget);
        $this->assertObjectHasAttribute('cumulative', $budget);
        $this->assertObjectHasAttribute('remaining', $budget);
        $this->assertObjectHasAttribute('transactionsCount', $budget);

        // Check if the values are correct according to our seeders!!
        $this->assertEquals("http://localhost/api/budgets/4", $budget->path);
        $this->assertEquals("busking", $budget->name);
        $this->assertEquals(10, $budget->amount);
        $this->assertEquals(20, $budget->calculatedAmount);
        $this->assertEquals('flex', $budget->type);
        $this->assertEquals("01/03/15", $budget->formattedStartingDate);
        $this->assertEquals(-35, $budget->spent);
        $this->assertEquals(1500, $budget->received);
        $this->assertEquals(-15, $budget->spentBeforeStartingDate);
        $this->assertEquals(-20, $budget->spentAfterStartingDate);
        $this->assertEquals(1000, $budget->receivedAfterStartingDate);
        $this->assertEquals(9, $budget->cumulativeMonthNumber);
        $this->assertEquals(null, $budget->cumulative);
        $this->assertEquals(1000, $budget->remaining);
        $this->assertEquals(6, $budget->transactionsCount);
    }

    /**
     * @test
     */
    public function it_checks_the_fixed_budget_totals_are_correct()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', 'api/totals/fixedBudget');
        $totals = json_decode($response->getContent(), false);

        $this->assertResponseOk();

        // Check if every attribute is present
        $this->assertObjectHasAttribute('amount', $totals);
        $this->assertObjectHasAttribute('remaining', $totals);
        $this->assertObjectHasAttribute('cumulative', $totals);
        $this->assertObjectHasAttribute('spentBeforeStartingDate', $totals);
        $this->assertObjectHasAttribute('spentAfterStartingDate', $totals);
        $this->assertObjectHasAttribute('receivedAfterStartingDate', $totals);

        // Check if the values are correct according to our seeders!!
        $this->assertEquals(200, $totals->amount);
        $this->assertEquals(1800, $totals->cumulative);
        $this->assertEquals(-30, $totals->spentBeforeStartingDate);
        $this->assertEquals(-40, $totals->spentAfterStartingDate);
        $this->assertEquals(200, $totals->receivedAfterStartingDate);
        $this->assertEquals(1960, $totals->remaining);
    }

    /**
     * @test
     */
    public function it_checks_the_flex_budget_totals_are_correct()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', 'api/totals/flexBudget');
        $totals = json_decode($response->getContent(), false);

        $this->assertResponseOk();
//        dd($totals);

        // Check if every attribute is present
        $this->assertObjectHasAttribute('allocatedAmount', $totals);
        $this->assertObjectHasAttribute('allocatedRemaining', $totals);
        $this->assertObjectHasAttribute('allocatedCalculatedAmount', $totals);
        $this->assertObjectHasAttribute('spentBeforeStartingDate', $totals);
        $this->assertObjectHasAttribute('spentAfterStartingDate', $totals);
        $this->assertObjectHasAttribute('receivedAfterStartingDate', $totals);

        $this->assertObjectHasAttribute('unallocatedAmount', $totals);
        $this->assertObjectHasAttribute('allocatedPlusUnallocatedAmount', $totals);
        $this->assertObjectHasAttribute('allocatedPlusUnallocatedCalculatedAmount', $totals);
        $this->assertObjectHasAttribute('unallocatedCalculatedAmount', $totals);
        $this->assertObjectHasAttribute('allocatedPlusUnallocatedRemaining', $totals);
        $this->assertObjectHasAttribute('unallocatedRemaining', $totals);

        // Check if the values are correct according to our seeders!!
        $this->assertEquals(20, $totals->allocatedAmount);
        $this->assertEquals(1020, $totals->allocatedRemaining);
        $this->assertEquals(40, $totals->allocatedCalculatedAmount);
        $this->assertEquals(-15, $totals->spentBeforeStartingDate);
        $this->assertEquals(-20, $totals->spentAfterStartingDate);
        $this->assertEquals(1000, $totals->receivedAfterStartingDate);

        $this->assertEquals(80, $totals->unallocatedAmount);
        $this->assertEquals(100, $totals->allocatedPlusUnallocatedAmount);
        $this->assertEquals(200, $totals->allocatedPlusUnallocatedCalculatedAmount);
        $this->assertEquals(160, $totals->unallocatedCalculatedAmount);
        $this->assertEquals(1180, $totals->allocatedPlusUnallocatedRemaining);
        $this->assertEquals(160, $totals->unallocatedRemaining);
    }




}
