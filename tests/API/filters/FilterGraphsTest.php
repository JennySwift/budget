<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

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

        $this->assertEquals('Jun 2013', $content['monthTotals'][0]['month']);
        $this->assertEquals('100', $content['monthTotals'][0]['positiveTransferTotal']);
        $this->assertEquals('-100', $content['monthTotals'][0]['negativeTransferTotal']);
        $this->assertEquals('0', $content['monthTotals'][1]['positiveTransferTotal']);
        $this->assertEquals('0', $content['monthTotals'][1]['negativeTransferTotal']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * This scenario was erroring for the frontend
     * @test
     * @return void
     */
    public function it_does_not_error_if_there_are_no_transactions_after_the_from_date_specified()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $date = Carbon::today()->subMonths(1)->startOfMonth()->format('Y-m-d');

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

        $response = $this->apiCall('POST', '/api/filter/graphTotals', $data);
        $content = json_decode($response->getContent(), true);

        $this->assertNull($content);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
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

        $this->assertEquals($date->copy()->addMonths(5)->format('M Y'), $content['monthTotals'][0]['month']);
        $this->assertEquals('855', $content['monthTotals'][0]['balanceFromBeginning']);
        $this->assertEquals('2255', $content['monthTotals'][3]['balanceFromBeginning']);
        $this->assertEquals('2190', $content['monthTotals'][4]['balanceFromBeginning']);

        $this->assertEquals('0', $content['monthTotals'][0]['negativeTransferTotal']);
        $this->assertEquals('0', $content['monthTotals'][0]['positiveTransferTotal']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_totals_spent_on_budgets_in_a_date_range()
    {
        $this->logInUser();

        $from = Carbon::today()->subMonths(3)->format('Y-m-d');
        $to = Carbon::today()->subMonths(2)->format('Y-m-d');

        $response = $this->call('GET', '/api/totals/spentOnBudgets?from=' . $from . '&to=' . $to);
        $content = json_decode($response->getContent(), true);
//      dd($content);

//        $this->checkTotalKeysExist($content[0]);
        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);

        $this->assertEquals('-5', $content[0]['spentInDateRange']);
        $this->assertEquals('-40', $content[1]['spentInDateRange']);
        $this->assertEquals('-20', $content[2]['spentInDateRange']);
        $this->assertEquals('0', $content[3]['spentInDateRange']);
        $this->assertEquals('0', $content[4]['spentInDateRange']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_totals_spent_on_budgets_without_a_date_range()
    {
        $this->logInUser();

        $response = $this->call('GET', '/api/totals/spentOnBudgets?from=' . '' . '&to=' . '');
        $content = json_decode($response->getContent(), true);
//      dd($content);

//        $this->checkTotalKeysExist($content[0]);
        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);

        $this->assertEquals('-5', $content[0]['spentInDateRange']);
        $this->assertEquals('-70', $content[1]['spentInDateRange']);
        $this->assertEquals('-35', $content[2]['spentInDateRange']);
        $this->assertEquals('0', $content[3]['spentInDateRange']);
        $this->assertEquals('0', $content[4]['spentInDateRange']);

        $this->assertEquals(200, $response->getStatusCode());
    }
}