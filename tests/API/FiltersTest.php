<?php

use App\Models\Budget;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

/**
 * Class FiltersTest
 * @VP:
 * You suggested it should be $filters rather than $filter, but is there
 * an easy way to do this renaming without breaking stuff? (It is throughout my app a lot.)
 * Changing all occurrences of 'filter' to 'filters' seems dangerous because that would change
 * existing occurrences of 'filters' to 'filterss.'
 *
 */
class FiltersTest extends TestCase {

    use DatabaseTransactions;

    /**
     * @var
     */
    protected $user;

    /**
     * @var
     */
    protected $defaults;

    /**
     * @var
     */
    protected $filter;

    /**
     * @var
     */
    protected $response;

    /**
     * @var
     */
    protected $transactions;
    protected $basicTotals;

    /**
     * @VP:
     * With the config line below, I got:
     * PHP Fatal error:  Call to a member function get() on null
     * Why? It works in my method below.
     */
//    public function __construct()
//    {
//        $this->defaults = Config::get('filters.defaults');
//    }

    /**
     *
     */
    private function setUser()
    {
        $this->user = $this->logInUser();
    }

    /**
     * Get the transactions
     * @param $data
     * @return Response
     */
    private function setTransactions($data)
    {
        $this->response = $this->apiCall('POST', '/api/filter/transactions', $data);
        $content = json_decode($this->response->getContent(), true);
        $this->transactions = $content;
    }

    /**
     * Get the basic filter totals
     * @return Response
     */
    private function setBasicTotals($data)
    {
//        dd($data);
        $this->response = $this->apiCall('POST', '/api/filter/basicTotals', $data);
        $content = json_decode($this->response->getContent(), true);
        $this->basicTotals = $content;
    }

    /**
     * Set the default filter
     */
    private function setFilterDefaults()
    {
        $this->defaults = Config::get('filters.defaults');
    }

    /**
 * @test
 * @return void
 */
    public function num_budgets_filter_works_when_value_is_no_budgets()
    {
        $this->setFilterDefaults();
        $this->setUser();

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

    /**
     * @test
     * @return void
     */
    public function it_checks_reconciled_filter_works_for_reconciled_transactions()
    {
        $this->setFilterDefaults();
        $this->setUser();

        $filter = [
            'reconciled' => 'true'
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        foreach ($this->transactions as $transaction) {
            $this->assertTrue($transaction['reconciled']);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_checks_reconciled_filter_works_for_unreconciled_transactions()
    {
        $this->setFilterDefaults();
        $this->setUser();

        $filter = [
            'reconciled' => 'false'
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        foreach ($this->transactions as $transaction) {
            $this->assertFalse($transaction['reconciled']);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_checks_filter_totals_are_correct_with_default_filters()
    {
        $this->setFilterDefaults();
        $this->setUser();

        $data = [
            'filter' => $this->defaults
        ];

        $this->setBasicTotals($data);

        $this->assertArrayHasKey('credit', $this->basicTotals);
        $this->assertArrayHasKey('debit', $this->basicTotals);
        $this->assertArrayHasKey('creditIncludingTransfers', $this->basicTotals);
        $this->assertArrayHasKey('debitIncludingTransfers', $this->basicTotals);
        $this->assertArrayHasKey('balance', $this->basicTotals);
        $this->assertArrayHasKey('reconciled', $this->basicTotals);
        $this->assertArrayHasKey('numTransactions', $this->basicTotals);

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
     * returned matches the num_to_fetch.
     * @test
     */
    public function it_checks_filter_totals_are_correct_when_num_to_fetch_is_low_enough_so_that_not_all_transactions_are_displayed()
    {
        $this->setFilterDefaults();
        $this->setUser();

        $filter = [
            'num_to_fetch' => 4,
            'offset' => 10
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];

        $this->setBasicTotals($data);
        $this->setTransactions($data);

        //Check the number of transactions returned matches the num_to_fetch
        $this->assertCount(4, $this->transactions);

        //Check the offset is working
        $this->assertEquals(3, $this->transactions[0]['id']);

        $this->assertArrayHasKey('credit', $this->basicTotals);
        $this->assertArrayHasKey('debit', $this->basicTotals);
        $this->assertArrayHasKey('creditIncludingTransfers', $this->basicTotals);
        $this->assertArrayHasKey('debitIncludingTransfers', $this->basicTotals);
        $this->assertArrayHasKey('balance', $this->basicTotals);
        $this->assertArrayHasKey('reconciled', $this->basicTotals);
        $this->assertArrayHasKey('numTransactions', $this->basicTotals);

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
        $this->setUser();

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

        $this->assertArrayHasKey('credit', $this->basicTotals);
        $this->assertArrayHasKey('debit', $this->basicTotals);
        $this->assertArrayHasKey('creditIncludingTransfers', $this->basicTotals);
        $this->assertArrayHasKey('debitIncludingTransfers', $this->basicTotals);
        $this->assertArrayHasKey('balance', $this->basicTotals);
        $this->assertArrayHasKey('reconciled', $this->basicTotals);
        $this->assertArrayHasKey('numTransactions', $this->basicTotals);

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
        $this->setUser();

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

        $this->assertArrayHasKey('credit', $this->basicTotals);
        $this->assertArrayHasKey('debit', $this->basicTotals);
        $this->assertArrayHasKey('creditIncludingTransfers', $this->basicTotals);
        $this->assertArrayHasKey('debitIncludingTransfers', $this->basicTotals);
        $this->assertArrayHasKey('balance', $this->basicTotals);
        $this->assertArrayHasKey('reconciled', $this->basicTotals);
        $this->assertArrayHasKey('numTransactions', $this->basicTotals);

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
        $this->setUser();

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

        $this->assertArrayHasKey('credit', $this->basicTotals);
        $this->assertArrayHasKey('debit', $this->basicTotals);
        $this->assertArrayHasKey('creditIncludingTransfers', $this->basicTotals);
        $this->assertArrayHasKey('debitIncludingTransfers', $this->basicTotals);
        $this->assertArrayHasKey('balance', $this->basicTotals);
        $this->assertArrayHasKey('reconciled', $this->basicTotals);
        $this->assertArrayHasKey('numTransactions', $this->basicTotals);

        $this->assertEquals(900, $this->basicTotals['credit']);
        $this->assertEquals(0, $this->basicTotals['debit']);
        $this->assertEquals(900, $this->basicTotals['creditIncludingTransfers']);
        $this->assertEquals(0, $this->basicTotals['debitIncludingTransfers']);
        $this->assertEquals(900, $this->basicTotals['balance']);
        $this->assertEquals(900, $this->basicTotals['reconciled']);
        $this->assertEquals(3, $this->basicTotals['numTransactions']);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }
}
