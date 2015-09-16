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
        $this->assertObjectHasAttribute('reconciled', $content);
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
        $this->assertEquals(160, $content->debit);
        $this->assertEquals(50, $content->savings);
        $this->assertEquals(2510, $content->balance);
        // @TODO Make the reconciled fixed as well so we can ensure it works as expected
        $this->assertEquals(50, $content->expensesWithoutBudget);
        $this->assertEquals(440, $content->remainingBalance);
        $this->assertEquals(2020, $content->remainingFixedBudget);
        $this->assertEquals(1800, $content->cumulativeFixedBudget);
        $this->assertEquals(20, $content->expensesWithFixedBudgetAfterStartingDate);
        $this->assertEquals(60, $content->expensesWithFixedBudgetBeforeStartingDate);
        $this->assertEquals(100, $content->expensesWithFlexBudgetAfterStartingDate);
        $this->assertEquals(30, $content->expensesWithFlexBudgetBeforeStartingDate);
    }
}
