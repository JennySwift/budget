<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class FilterBudgetsTest
 */
class FilterBudgetsTest extends FiltersTest
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_checks_the_budget_filter_in_works_with_and()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'budgets' => [
                'in' => [
                    'and' => [
                        ['id' => 1]
                    ],
                    'or' => []
                ],
                'out' => []
            ]
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        //Check each transaction has the budget with the id of 1
        foreach ($this->transactions as $transaction) {
            $counter = 0;
            foreach ($transaction['budgets'] as $budget) {
                if ($budget['id'] == 1) {
                    $counter++;
                }
            }
            $this->assertGreaterThan(0, $counter);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_checks_the_budget_filter_in_works_with_and_and_multiple_budgets()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'budgets' => [
                'in' => [
                    'and' => [
                        ['id' => 2], ['id' => 4]
                    ],
                    'or' => []
                ],
                'out' => []
            ]
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        //Check each transaction has both budgets (budget ids 2 and 4)
        foreach ($this->transactions as $transaction) {
            $counter1 = 0;
            $counter2 = 0;
            foreach ($transaction['budgets'] as $budget) {
                if ($budget['id'] == 2) {
                    $counter1++;
                }
                else if ($budget['id'] == 4) {
                    $counter2++;
                }
            }
            $this->assertGreaterThan(0, $counter1);
            $this->assertGreaterThan(0, $counter2);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_checks_the_budget_filter_in_works_with_or()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'budgets' => [
                'in' => [
                    'and' => [],
                    'or' => [
                        ['id' => 2], ['id' => 4]
                    ]
                ],
                'out' => []
            ]
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        //Check each transaction has at least one of the budgets (budget ids 2 or 4)
        //Todo: Check there are no transactions in the database that should have been included in the results?
        foreach ($this->transactions as $transaction) {
            $counter = 0;
            foreach ($transaction['budgets'] as $budget) {
                if ($budget['id'] == 2 || $budget['id'] == 4) {
                    $counter++;
                }
            }
            $this->assertGreaterThan(0, $counter);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_checks_the_budget_filter_out_works()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'budgets' => [
                'in' => [
                    'and' => [],
                    'or' => []
                ],
                'out' => [
                    ['id' => 1]
                ]
            ]
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        //Check each transaction has the budget with the id of 1
        foreach ($this->transactions as $transaction) {
            $counter = 0;
            foreach ($transaction['budgets'] as $budget) {
                if ($budget['id'] == 1) {
                    $counter++;
                }
            }
            $this->assertEquals(0, $counter);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }


}