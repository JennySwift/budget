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
    private function setProperties()
    {
        $this->user = $this->logInUser();
    }

    /**
     * Get the filter response
     * @VP:
     * Is there a way I can access the data like $content->transactions rather than $content['transactions']?
     * And $transaction->budgets rather than $transaction['budgets']?
     * @return Response
     */
    private function getResponse($data)
    {
        $this->response = $this->apiCall('POST', '/api/filter/transactions', $data);
        $content = json_decode($this->response->getContent(), true);
        $this->transactions = $content['transactions'];
    }

    /**
     * Set the default filter
     */
    private function setDefaults()
    {
        $this->defaults = Config::get('filters.defaults');
    }

    /**
     * @test
     * @return void
     */
    public function num_budgets_filter_works_when_value_is_no_budgets()
    {
        $this->setDefaults();
        $this->setProperties();

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
        $this->getResponse($data);

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
