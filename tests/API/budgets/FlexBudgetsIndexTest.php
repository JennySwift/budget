<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class FlexBudgetsIndexTest
 */
class FlexBudgetsIndexTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_flex_budgets()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/budgets?flex=true');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkBudgetKeysExist($content[0]);

        foreach ($content as $budget) {
            $this->assertEquals('flex', $budget['type']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }
}