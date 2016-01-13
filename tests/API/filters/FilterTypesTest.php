<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class FilterTypesTest
 */
class FilterTypesTest extends FiltersTest
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_checks_the_types_filter_in_works()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'types' => [
                'in' => [
                    'income'
                ],
                'out' => []
            ]
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        foreach ($this->transactions as $transaction) {
            $this->assertEquals('income', $transaction['type']);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_checks_the_types_filter_out_works()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'types' => [
                'in' => [],
                'out' => ['income']
            ]
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        foreach ($this->transactions as $transaction) {
            $this->assertNotEquals('income', $transaction['type']);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

}