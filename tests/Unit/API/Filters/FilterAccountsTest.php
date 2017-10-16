<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class FilterAccountsTest
 */
class FilterAccountsTest extends FiltersTest
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_checks_the_account_in_filter_works()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'accounts' => [
                'in' => [
                    1
                ],
                'out' => []
            ]
        ];

        $response = $this->getResponse($filter);
        $content = json_decode($response->getContent(), true);

        foreach ($content as $transaction) {
            $this->assertEquals(1, $transaction['account']['id']);
        }

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_checks_the_account_out_filter_works()
    {
        $this->setFilterDefaults();
        $this->logInUser();

        $filter = [
            'accounts' => [
                'in' => [],
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