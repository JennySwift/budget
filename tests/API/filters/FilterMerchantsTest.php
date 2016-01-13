<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class FilterMerchantsTest
 */
class FilterMerchantsTest extends FiltersTest
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_checks_the_merchants_filter_in_works()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'merchant' => [
                'in' => 'e',
                'out' => ''
            ]
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        foreach ($this->transactions as $transaction) {
            $this->assertContains('e', $transaction['merchant'], '', true);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_checks_the_merchants_filter_out_works()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'merchant' => [
                'in' => '',
                'out' => 'e'
            ]
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        foreach ($this->transactions as $transaction) {
            $this->assertNotContains('e', $transaction['merchant'], '', true);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

}