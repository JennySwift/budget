<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class ExceptionsTest
 */
class ExceptionsTest extends FiltersTest
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_returns_useful_info_if_there_is_an_exception()
    {
        $this->logInUser();

        $data = [
            'filter' => ['invalidfield' => '']
        ];
        $this->setTransactions($data);



        $content = $this->getContent($this->response);
//        dd($content['request']);
        $this->assertEquals("Undefined index: invalidAllocation", $content['error']);
        $this->assertEquals('filter%5Binvalidfield%5D=', $content['request']['queryString']);
        $this->assertEquals('/api/filter/transactions?filter%5Binvalidfield%5D=', $content['request']['requestUri']);

        $expected = [
            "filter" => [
                "invalidfield" => ""
            ]
        ];
        $this->assertEquals($expected, $content['request']['toArray']);

//        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_still_works_if_a_filter_field_is_not_sent_in_the_request()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'accounts' => [
                //After upgrading Vue, vue-resource stopped sending empty arrays with the request, so the backend started erroring.
                //Testing it doesn't error anymore if the 'in' field is not specified.
//                'in' => [],
                'out' => [1]
            ]
        ];

        $response = $this->getResponse($filter);
        $content = json_decode($response->getContent(), true);

        foreach ($content as $transaction) {
            $this->assertNotEquals(1, $transaction['account']['id']);
        }

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}