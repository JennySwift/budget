<?php

use App\Models\SavedFilter;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class SavedFiltersTest
 */
class SavedFiltersDestroyTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_delete_a_saved_filter()
    {
        DB::beginTransaction();
        $this->logInUser();

        $savedFilter = SavedFilter::first();

        $response = $this->call('DELETE', '/api/savedFilters/'.$savedFilter->id);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->call('DELETE', '/api/savedFilters/' . $savedFilter->id);
//        dd($response);
        $this->assertEquals(404, $response->getStatusCode());

        DB::rollBack();
    }
}