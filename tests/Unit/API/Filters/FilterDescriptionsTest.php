<?php

use App\Models\Transaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

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

    /**
     * @test
     * @return void
     */
    public function it_does_not_filter_out_more_transactions_than_it_should_when_the_description_filter_out_is_used()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        //Count the number of transactions that do contain 'e' in the description
        //Todo: perhaps make seeder consistent so it's the same each time

        $count = Transaction::forCurrentUser()->where('description', 'LIKE', '%e%')->count();

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

        $this->checkTransactionKeysExist($this->transactions[0]);

        foreach ($this->transactions as $transaction) {
            if ($transaction['merchant']) {
                $this->assertNotContains('e', $transaction['description'], '', true);
            }
        }

        //There are 16 transactions in total, so there should be 16 - $count in the filtered results
        $this->assertCount(16 - $count, $this->transactions);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

}