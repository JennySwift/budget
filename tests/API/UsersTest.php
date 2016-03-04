<?php

use App\Models\Preference;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class UsersTest
 */
class UsersTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * @test
     * @return void
     */
    public function it_can_update_the_preferences_for_a_user()
    {
        DB::beginTransaction();
        $this->logInUser();

        $response = $this->call('PUT', '/api/users/'.$this->user->id, [
            'preferences' => [
                'clearFields' => true,
                'colors' => [
                    'income' => 'pink',
                    'expense' => 'blue',
                    'transfer' => 'purple'
                ]
            ]
        ]);
        $preferences = json_decode($response->getContent(), true)['preferences'];
//        dd($preferences);

        $this->checkPreferencesKeysExist($preferences);

        $this->assertTrue($preferences['clearFields']);
        $this->assertEquals('pink', $preferences['colors']['income']);
        $this->assertEquals('blue', $preferences['colors']['expense']);
        $this->assertEquals('purple', $preferences['colors']['transfer']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

}