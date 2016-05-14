<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class SavedFiltersTest
 */
class SavedFiltersIndexTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_saved_filters()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/savedFilters');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkSavedFilterKeysExist($content[0]);

        $this->assertEquals('bank account expenses', $content[0]['name']);

        $this->assertEquals(200, $response->getStatusCode());
    }
}