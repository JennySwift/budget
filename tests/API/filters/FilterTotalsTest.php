<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class FilterTotalsTest
 */
class FilterTotalsTest extends FiltersTest
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_checks_filter_totals_are_correct_with_default_filters()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $data = [
            'filter' => $this->defaults
        ];

        $this->setBasicTotals($data);

        $this->checkBasicTotalKeysExist($this->basicTotals);

        $this->assertEquals(2350, $this->basicTotals['credit']);
        $this->assertEquals(-160, $this->basicTotals['debit']);
        $this->assertEquals(2450, $this->basicTotals['creditIncludingTransfers']);
        $this->assertEquals(-260, $this->basicTotals['debitIncludingTransfers']);
        $this->assertEquals(2190, $this->basicTotals['balance']);
        $this->assertEquals(1050, $this->basicTotals['reconciled']);
        $this->assertEquals(16, $this->basicTotals['numTransactions']);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * Also checks the offset is working, and that the number of transactions
     * returned matches the numToFetch.
     * @test
     */
    public function it_checks_filter_totals_are_correct_when_num_to_fetch_is_low_enough_so_that_not_all_transactions_are_displayed()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'numToFetch' => 4,
            'offset' => 10
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];

        $this->setBasicTotals($data);
        $this->setTransactions($data);

        //Check the number of transactions returned matches the numToFetch
        $this->assertCount(4, $this->transactions);

        //Check the offset is working
        $this->assertEquals(3, $this->transactions[0]['id']);

        $this->checkBasicTotalKeysExist($this->basicTotals);

        $this->assertEquals(2350, $this->basicTotals['credit']);
        $this->assertEquals(-160, $this->basicTotals['debit']);
        $this->assertEquals(2450, $this->basicTotals['creditIncludingTransfers']);
        $this->assertEquals(-260, $this->basicTotals['debitIncludingTransfers']);
        $this->assertEquals(2190, $this->basicTotals['balance']);
        $this->assertEquals(1050, $this->basicTotals['reconciled']);
        $this->assertEquals(16, $this->basicTotals['numTransactions']);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_checks_filter_totals_are_correct_with_single_date_filter()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'single_date' => [
                'inSql' => '2015-08-01',
                "outSql" => ""
            ]
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];

        $this->setBasicTotals($data);
        $this->setTransactions($data);

        //Check the number of transactions returned is correct
        $this->assertCount(4, $this->transactions);

        $this->checkBasicTotalKeysExist($this->basicTotals);

        $this->assertEquals(0, $this->basicTotals['credit']);
        $this->assertEquals(-65, $this->basicTotals['debit']);
        $this->assertEquals(0, $this->basicTotals['creditIncludingTransfers']);
        $this->assertEquals(-65, $this->basicTotals['debitIncludingTransfers']);
        $this->assertEquals(-65, $this->basicTotals['balance']);
        $this->assertEquals(-5, $this->basicTotals['reconciled']);
        $this->assertEquals(4, $this->basicTotals['numTransactions']);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_checks_filter_totals_are_correct_with_from_and_to_date_filters()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'from_date' => [
                'inSql' => '2013-02-01',
                "outSql" => ""
            ],
            'to_date' => [
                'inSql' => '2015-08-01',
                "outSql" => ""
            ]

        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];

        $this->setBasicTotals($data);
        $this->setTransactions($data);

        //Check the number of transactions returned is correct
        $this->assertCount(10, $this->transactions);

        $this->checkBasicTotalKeysExist($this->basicTotals);

        $this->assertEquals(900, $this->basicTotals['credit']);
        $this->assertEquals(-115, $this->basicTotals['debit']);
        $this->assertEquals(1000, $this->basicTotals['creditIncludingTransfers']);
        $this->assertEquals(-215, $this->basicTotals['debitIncludingTransfers']);
        $this->assertEquals(785, $this->basicTotals['balance']);
        $this->assertEquals(845, $this->basicTotals['reconciled']);
        $this->assertEquals(10, $this->basicTotals['numTransactions']);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_checks_filter_totals_are_correct_with_from_and_to_date_filters_and_type_income_filter()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'from_date' => [
                'inSql' => '2013-02-01',
                "outSql" => ""
            ],
            'to_date' => [
                'inSql' => '2015-08-01',
                "outSql" => ""
            ],
            'types' => [
                'in' => ['income'],
                'out' => []
            ],

        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];

        $this->setBasicTotals($data);
        $this->setTransactions($data);

        //Check the number of transactions returned is correct
        $this->assertCount(3, $this->transactions);

        $this->checkBasicTotalKeysExist($this->basicTotals);

        $this->assertEquals(900, $this->basicTotals['credit']);
        $this->assertEquals(0, $this->basicTotals['debit']);
        $this->assertEquals(900, $this->basicTotals['creditIncludingTransfers']);
        $this->assertEquals(0, $this->basicTotals['debitIncludingTransfers']);
        $this->assertEquals(900, $this->basicTotals['balance']);
        $this->assertEquals(900, $this->basicTotals['reconciled']);
        $this->assertEquals(3, $this->basicTotals['numTransactions']);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_checks_filter_totals_are_correct_with_from_and_to_date_filters_and_type_expense_filter_and__business_tag_filter()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'from_date' => [
                'inSql' => '2013-02-01',
                "outSql" => ""
            ],
            'to_date' => [
                'inSql' => '2015-08-01',
                "outSql" => ""
            ],
            'types' => [
                'in' => ['expense'],
                'out' => []
            ],
            "budgets" => [
                "in" => [
                    "and" => [
                        //business budget
                        ['id' => 2]
                    ],
                    "or" => []
                ],
                "out" => []
            ],

        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];

        $this->setBasicTotals($data);
        $this->setTransactions($data);

        //Check the number of transactions returned is correct
        $this->assertCount(2, $this->transactions);

        $this->checkBasicTotalKeysExist($this->basicTotals);

        $this->assertEquals(0, $this->basicTotals['credit']);
        $this->assertEquals(-40, $this->basicTotals['debit']);
        $this->assertEquals(0, $this->basicTotals['creditIncludingTransfers']);
        $this->assertEquals(-40, $this->basicTotals['debitIncludingTransfers']);
        $this->assertEquals(-40, $this->basicTotals['balance']);
        $this->assertEquals(0, $this->basicTotals['reconciled']);
        $this->assertEquals(2, $this->basicTotals['numTransactions']);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_checks_filter_totals_are_correct_with_from_and_to_date_filters_and_type_expense_filter_and__busking_tag_filter()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'from_date' => [
                'inSql' => '2013-02-01',
                "outSql" => ""
            ],
            'to_date' => [
                'inSql' => '2015-08-01',
                "outSql" => ""
            ],
            'types' => [
                'in' => ['expense'],
                'out' => []
            ],
            "budgets" => [
                "in" => [
                    "and" => [
                        //busking budget
                        ['id' => 4]
                    ],
                    "or" => []
                ],
                "out" => []
            ],

        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];

        $this->setBasicTotals($data);
        $this->setTransactions($data);

        //Check the number of transactions returned is correct
        $this->assertCount(2, $this->transactions);

        $this->checkBasicTotalKeysExist($this->basicTotals);

        $this->assertEquals(0, $this->basicTotals['credit']);
        $this->assertEquals(-50, $this->basicTotals['debit']);
        $this->assertEquals(0, $this->basicTotals['creditIncludingTransfers']);
        $this->assertEquals(-50, $this->basicTotals['debitIncludingTransfers']);
        $this->assertEquals(-50, $this->basicTotals['balance']);
        $this->assertEquals(0, $this->basicTotals['reconciled']);
        $this->assertEquals(2, $this->basicTotals['numTransactions']);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_checks_filter_totals_are_correct_with_from_and_to_date_filters_and_type_expense_filter_and__bank_fees_tag_filter()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'from_date' => [
                'inSql' => '2013-02-01',
                "outSql" => ""
            ],
            'to_date' => [
                'inSql' => '2015-08-01',
                "outSql" => ""
            ],
            'types' => [
                'in' => ['expense'],
                'out' => []
            ],
            "budgets" => [
                "in" => [
                    "and" => [
                        //bank fees budget
                        ['id' => 1]
                    ],
                    "or" => []
                ],
                "out" => []
            ],

        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];

        $this->setBasicTotals($data);
        $this->setTransactions($data);

        //Check the number of transactions returned is correct
        $this->assertCount(1, $this->transactions);

        $this->checkBasicTotalKeysExist($this->basicTotals);

        $this->assertEquals(0, $this->basicTotals['credit']);
        $this->assertEquals(-5, $this->basicTotals['debit']);
        $this->assertEquals(0, $this->basicTotals['creditIncludingTransfers']);
        $this->assertEquals(-5, $this->basicTotals['debitIncludingTransfers']);
        $this->assertEquals(-5, $this->basicTotals['balance']);
        $this->assertEquals(-5, $this->basicTotals['reconciled']);
        $this->assertEquals(1, $this->basicTotals['numTransactions']);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_checks_filter_totals_are_correct_with_from_and_to_date_filters_and_type_income_filter_and__busking_tag_filter()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'from_date' => [
                'inSql' => '2013-02-01',
                "outSql" => ""
            ],
            'to_date' => [
                'inSql' => '2015-08-01',
                "outSql" => ""
            ],
            'types' => [
                'in' => ['income'],
                'out' => []
            ],
            "budgets" => [
                "in" => [
                    "and" => [
                        //busking budget
                        ['id' => 4]
                    ],
                    "or" => []
                ],
                "out" => []
            ],

        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];

        $this->setBasicTotals($data);
        $this->setTransactions($data);

        //Check the number of transactions returned is correct
        $this->assertCount(1, $this->transactions);

        $this->checkBasicTotalKeysExist($this->basicTotals);

        $this->assertEquals(500, $this->basicTotals['credit']);
        $this->assertEquals(0, $this->basicTotals['debit']);
        $this->assertEquals(500, $this->basicTotals['creditIncludingTransfers']);
        $this->assertEquals(0, $this->basicTotals['debitIncludingTransfers']);
        $this->assertEquals(500, $this->basicTotals['balance']);
        $this->assertEquals(500, $this->basicTotals['reconciled']);
        $this->assertEquals(1, $this->basicTotals['numTransactions']);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

}