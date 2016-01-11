<?php

use App\Models\Budget;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class BudgetsDestroyTest
 */
class BudgetsDestroyTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_deletes_a_budget()
    {
        $this->logInUser();

        $budget = Budget::forCurrentUser()->first();

        $response = $this->apiCall('DELETE', '/api/budgets/'.$budget->id);

        $this->assertEquals(204, $response->getStatusCode());
        $this->missingFromDatabase('budgets', [
            'user_id' => $this->user->id,
            'name' => $budget->name
        ]);
    }

}