<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class FilterNumBudgetsTest
 */
class FilterNumBudgetsTest extends FiltersTest
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function num_budgets_filter_works_when_value_is_no_budgets()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'numBudgets' => [
                "in" => "zero",
                "out" => "none"
            ]
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        //Check the budgets of all the transactions,
        //that there are no type 'fixed' or type 'flex.'
        foreach ($this->transactions as $transaction) {
            foreach ($transaction['budgets'] as $budget) {
                $this->assertNotEquals('fixed', $budget['type']);
                $this->assertNotEquals('flex', $budget['type']);
            }
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }


}