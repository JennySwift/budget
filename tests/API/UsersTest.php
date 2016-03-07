<?php

use App\Models\Preference;
use App\User;
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

        $preferences = User::find($this->user->id)->preferences;

        //Check the values are as expected before the update
        $this->assertFalse($preferences['clearFields']);
        $this->assertEquals('DD/MM/YY', $preferences['dateFormat']);
        $this->assertTrue($preferences['autocompleteDescription']);
        $this->assertTrue($preferences['autocompleteMerchant']);

        $response = $this->call('PUT', '/api/users/'.$this->user->id, [
            'preferences' => [
                'clearFields' => true,
                'colors' => [
                    'income' => 'pink',
                    'expense' => 'blue',
                    'transfer' => 'purple'
                ],
                'dateFormat' => 'dd/mm/yyyy',
                'autocompleteDescription' => false,
                'autocompleteMerchant' => false
            ]
        ]);
        $preferences = json_decode($response->getContent(), true)['preferences'];
//        dd($preferences);

        $this->checkPreferencesKeysExist($preferences);

        $this->assertTrue($preferences['clearFields']);
        $this->assertEquals('pink', $preferences['colors']['income']);
        $this->assertEquals('blue', $preferences['colors']['expense']);
        $this->assertEquals('purple', $preferences['colors']['transfer']);
        $this->assertEquals('dd/mm/yyyy', $preferences['dateFormat']);
        $this->assertFalse($preferences['autocompleteDescription']);
        $this->assertFalse($preferences['autocompleteMerchant']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

}