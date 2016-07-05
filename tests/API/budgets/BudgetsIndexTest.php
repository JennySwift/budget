<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class BudgetsIndexTest
 */
class BudgetsIndexTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_budgets()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/budgets');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkBudgetKeysExist($content[0]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_unassigned_budgets()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/budgets?unassigned=true');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkBudgetKeysExist($content[0]);

        foreach ($content as $budget) {
            $this->assertEquals('unassigned', $budget['type']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }
}