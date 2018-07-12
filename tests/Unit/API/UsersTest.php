<?php

use App\Models\Preference;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class UsersTest
 */
class UsersTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_show_the_logged_in_user_when_their_id_is_known()
    {
        $this->logInUser();

        $response = $this->call('GET', '/api/users/' . $this->user->id);
        $content = $this->getContent($response);
//        dd($content);

        $this->checkUserKeysExist($content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals("Dummy", $content['name']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_show_the_logged_in_user_when_their_id_is_not_known()
    {
        $this->logInUser();

        $response = $this->call('GET', '/api/users/current');
        $content = $this->getContent($response);
//        dd($content);

        $this->checkUserKeysExist($content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals("Dummy", $content['name']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
    
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
                'autocompleteMerchant' => false,

                'show' => [
                    'totals' => [
                        'credit' => false,
                        'remainingFixedBudget' => false,
                        'expensesWithoutBudget' => false,
                        'expensesWithFixedBudgetBeforeStartingDate' => false,
                        'expensesWithFixedBudgetAfterStartingDate' => false,
                        'expensesWithFlexBudgetBeforeStartingDate' => false,
                        'expensesWithFlexBudgetAfterStartingDate' => false,
                        'savings' => false,
                        'remainingBalance' => false,
                        'debit' => false,
                        'balance' => false,
                        'reconciled' => false,
                        'cumulativeFixedBudget' => false,
                    ],
                    //todo: Don't allow this
                    'hack' => 'blah'
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
        $this->assertEquals('dd/mm/yyyy', $preferences['dateFormat']);
        $this->assertFalse($preferences['autocompleteDescription']);
        $this->assertFalse($preferences['autocompleteMerchant']);

        $this->assertFalse($preferences['show']['totals']['credit']);
        $this->assertFalse($preferences['show']['totals']['remainingFixedBudget']);
        $this->assertFalse($preferences['show']['totals']['expensesWithoutBudget']);
        $this->assertFalse($preferences['show']['totals']['expensesWithFixedBudgetBeforeStartingDate']);
        $this->assertFalse($preferences['show']['totals']['expensesWithFixedBudgetAfterStartingDate']);
        $this->assertFalse($preferences['show']['totals']['expensesWithFlexBudgetBeforeStartingDate']);
        $this->assertFalse($preferences['show']['totals']['expensesWithFlexBudgetAfterStartingDate']);
        $this->assertFalse($preferences['show']['totals']['savings']);
        $this->assertFalse($preferences['show']['totals']['remainingBalance']);
        $this->assertFalse($preferences['show']['totals']['debit']);
        $this->assertFalse($preferences['show']['totals']['balance']);
        $this->assertFalse($preferences['show']['totals']['reconciled']);
        $this->assertFalse($preferences['show']['totals']['cumulativeFixedBudget']);
//        $this->assertArrayNotHasKey('hack', $preferences['show']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

}