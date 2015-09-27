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
        // @TODO Make the reconciled fixed as well so we can ensure it works as expected
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
        $this->assertEquals("http://localhost/api/budgets/2", $budget->path);
        $this->assertEquals("business", $budget->name);
        $this->assertEquals(100, $budget->amount);
        $this->assertEquals(null, $budget->calculatedAmount);
        $this->assertEquals('fixed', $budget->type);
        $this->assertEquals("01/01/15", $budget->formattedStartingDate);
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
        $this->assertEquals("01/01/15", $budget->formattedStartingDate);
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
}
