<?php

use App\Models\Budget;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

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
        $content = json_decode($response->getContent(), false);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

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
}
