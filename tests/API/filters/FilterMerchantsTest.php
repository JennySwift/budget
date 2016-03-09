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

//        dd($this->transactions[0]);

        $this->checkTransactionKeysExist($this->transactions[0]);

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

        $this->checkTransactionKeysExist($this->transactions[0]);

        foreach ($this->transactions as $transaction) {
            if ($transaction['merchant']) {
                $this->assertNotContains('e', $transaction['merchant'], '', true);
            }
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_does_not_filter_out_more_transactions_than_it_should_when_the_merchant_filter_out_is_used()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'merchant' => [
                'in' => '',
                'out' => 'sally'
            ]
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        $this->checkTransactionKeysExist($this->transactions[0]);

        foreach ($this->transactions as $transaction) {
            if ($transaction['merchant']) {
                $this->assertNotContains('sally', $transaction['merchant'], '', true);
            }
        }

        //There is only one transaction that should be filtered out, so check the number is right
        $this->assertCount(15, $this->transactions);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

}