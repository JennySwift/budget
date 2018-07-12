<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class FilterReconciledTest
 */
class FilterReconciledTest extends FiltersTest
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_checks_reconciled_filter_works_for_reconciled_transactions()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'reconciled' => 'true'
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        foreach ($this->transactions as $transaction) {
            $this->assertTrue($transaction['reconciled']);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_checks_reconciled_filter_works_for_unreconciled_transactions()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'reconciled' => 'false'
        ];

        $this->filter = array_merge($this->defaults, $filter);

        $data = [
            'filter' => $this->filter
        ];
        $this->setTransactions($data);

        foreach ($this->transactions as $transaction) {
            $this->assertFalse($transaction['reconciled']);
        }

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }


}