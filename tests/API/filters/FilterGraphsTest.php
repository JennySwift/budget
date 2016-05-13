<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Todo
 * Class FilterGraphsTest
 */
class FilterGraphsTest extends FiltersTest
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_graph_totals()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [];
        $this->filter = array_merge($this->defaults, $filter);
        $data = [
            'filter' => $this->filter
        ];

        $response = $this->apiCall('POST', '/api/filter/graphTotals', $data);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkGraphTotalKeysExist($content);
        $this->assertArrayHasKey('maxTotal', $content);

        $this->assertEquals('Jun 2013', $content['monthsTotals'][0]['month']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_graph_totals_with_the_correct_balances_at_the_time_for_each_month()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $date = Carbon::today()->subMonths(12);
        $filter = [
            'fromDate' => [
                'inSql' => $date->copy()->format('Y-m-d'),
                'outSql' => ''
            ]
        ];
        $this->filter = array_merge($this->defaults, $filter);
        $data = [
            'filter' => $this->filter
        ];

        $response = $this->apiCall('POST', '/api/filter/graphTotals', $data);
//        dd($response);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkGraphTotalKeysExist($content);
        $this->assertArrayHasKey('maxTotal', $content);

        $this->assertEquals($date->copy()->addMonths(5)->format('M Y'), $content['monthsTotals'][0]['month']);
        $this->assertEquals('855', $content['monthsTotals'][0]['balanceFromBeginning']);
        $this->assertEquals('2255', $content['monthsTotals'][3]['balanceFromBeginning']);
        $this->assertEquals('2190', $content['monthsTotals'][4]['balanceFromBeginning']);

        $this->assertEquals(200, $response->getStatusCode());
    }

}