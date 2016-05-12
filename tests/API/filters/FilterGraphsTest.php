<?php

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

}