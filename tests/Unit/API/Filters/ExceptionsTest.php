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
//        $this->setFilterDefaults();
        $this->logInUser();
//
//        $filter = [
//            'types' => [
//                'in' => [
//                    'income'
//                ],
//                'out' => []
//            ]
//        ];
//
//        $this->filter = array_merge($this->defaults, $filter);
//
        $data = [
//            'filter' => $this->filter
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
}