<?php

use App\Models\Budget;
use App\Models\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

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
     * @var
     */
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
     * Get the transactions
     * @param $data
     * @return Response
     */
    protected function setTransactions($data)
    {
        $this->response = $this->apiCall('POST', '/api/filter/transactions', $data);
        $content = json_decode($this->response->getContent(), true);
        $this->transactions = $content;
    }

    /**
     * Get the basic filter totals
     * @return Response
     */
    protected function setBasicTotals($data)
    {
        $this->response = $this->apiCall('POST', '/api/filter/basicTotals', $data);
        $content = json_decode($this->response->getContent(), true);
        $this->basicTotals = $content;
    }

    /**
     * Set the default filter
     */
    protected function setFilterDefaults()
    {
        $this->defaults = Config::get('filters.defaults');
    }

    /**
     * @test
     * @return void
     */
    public function it_checks_the_bug_fix_method_works()
    {
//        $this->setFilterDefaults();
//        $this->logInUser();
//
//        $filter = [
//            'bugFix' => 'true'
//        ];
//
//        $this->filter = array_merge($this->defaults, $filter);
//
//        $data = [
//            'filter' => $this->filter
//        ];
//        $this->setTransactions($data);
//
//
////        foreach ($this->transactions as $transaction) {
////            $transaction = Transaction::find($transaction['id']);
////            dd($transaction);
////        }
//
//        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }
}
