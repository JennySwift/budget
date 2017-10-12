<?php

use App\Models\Budget;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

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

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());

        $response = $this->apiCall('DELETE', '/api/budgets/'.$budget->id);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

}