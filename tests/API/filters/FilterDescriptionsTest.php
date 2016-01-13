<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class FilterDescriptionsTest
 */
class FilterDescriptionsTest extends FiltersTest
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_checks_the_descriptions_filter_in_works()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'description' => [
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
            $this->assertContains('e', $transaction['description'], '', true);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_checks_the_descriptions_filter_out_works()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'description' => [
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
            $this->assertNotContains('e', $transaction['description'], '', true);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

}