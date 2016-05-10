<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class FilterDatesTest
 */
class FilterDatesTest extends FiltersTest
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_checks_the_singleDate_filter_in_works()
    {
        $this->setFilterDefaults();
        $this->logInUser();

//        $date = Carbon::yesterday()->format('Y-m-d');
        $date = Carbon::today()->subMonths(3)->format('Y-m-d');

        $filter = [
            'singleDate' => [
                'inSql' => $date,
                'outSql' => ''
            ]
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        $this->assertGreaterThan(0, count($this->transactions));

        foreach ($this->transactions as $transaction) {
            $this->assertEquals($date, $transaction['date']);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_checks_the_singleDate_filter_out_works()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $date = Carbon::today()->subMonths(3)->format('Y-m-d');

        $filter = [
            'singleDate' => [
                'inSql' => '',
                'outSql' => $date
            ]
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

//        dd($this->transactions);

        foreach ($this->transactions as $transaction) {
            $this->assertNotEquals($date, $transaction['date']);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_checks_the_fromDate_in_filter_works()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $date = Carbon::today()->subMonths(3)->format('Y-m-d');

        $filter = [
            'fromDate' => [
                'inSql' => $date,
                'outSql' => ''
            ]
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        foreach ($this->transactions as $transaction) {
            $this->assertTrue(Carbon::createFromFormat('Y-m-d', $transaction['date']) >= Carbon::createFromFormat('Y-m-d', $date));
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_checks_the_toDate_in_filter_works()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $date = Carbon::today()->subMonths(3)->format('Y-m-d');

        $filter = [
            'toDate' => [
                'inSql' => $date,
                'outSql' => ''
            ]
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        foreach ($this->transactions as $transaction) {
            $this->assertTrue(Carbon::createFromFormat('Y-m-d', $transaction['date']) <= Carbon::createFromFormat('Y-m-d', $date));
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

}