<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class FilterDefaultsTest
 */
class FilterDefaultsTest extends FiltersTest
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_transactions_with_the_default_filter()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];

        $response = $this->apiCall('GET', '/api/filter/transactions', $data);
        $content = json_decode($response->getContent(), true);

        $this->checkTransactionKeysExist($content[0]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

}