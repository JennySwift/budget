<?php

use App\Models\Account;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AccountsTest extends TestCase {

    use DatabaseTransactions;

	/**
	 * A basic functional test example.
	 * @test
	 * @return void
	 */
	public function it_lists_all_the_accounts()
	{
        $this->logInUser();

        $accounts = Account::all();

		$response = $this->call('GET', '/api/accounts');
        $content = json_decode($response->getContent(), true);

		$this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('name', $content[0]);
        $this->assertArrayHasKey('user_id', $content[0]);
        $this->assertArrayHasKey('path', $content[0]);
        $this->assertContains($accounts->first()->name, $content[0]);
        $this->assertContains($accounts->get(1)->name, $content[1]);
	}

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_displays_an_account()
    {
        $user = $this->logInUser();

        $account = Account::forCurrentUser()->first();

        $response = $this->call('GET', '/api/accounts/'.$account->id);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('user_id', $content);
        $this->assertArrayHasKey('path', $content);
        $this->assertContains($account->name, $content);
        $this->assertContains($account->path, $content);
        $this->assertEquals($user->id, $content['user_id']);
    }

	/**
	 * A basic functional test example.
	 * @test
	 * @return void
	 */
	public function it_can_add_a_new_account()
	{
        $this->logInUser();

        $account = [
            'name' => 'kangaroo'
        ];

        $response = $this->call('POST', '/api/accounts', $account);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('user_id', $content);
        $this->assertArrayHasKey('path', $content);
        $this->assertContains($account['name'], $content);
	}

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_cannot_add_a_new_account_without_proper_data()
    {
        $this->logInUser();

        // No name provided
        $response = $this->apiCall('POST', '/api/accounts', []);
        $this->assertEquals(422, $response->getStatusCode());

        // Duplicate test
        $account = [
            'name' => 'kangaroo'
        ];
        $this->apiCall('POST', '/api/accounts', $account); // Insert kangaroo
        $response = $this->apiCall('POST', '/api/accounts', $account); // This one should fail because we inserted it before :)
        $this->assertEquals(422, $response->getStatusCode());
    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_can_update_an_existing_account()
    {
        $this->logInUser();

        $account = Account::forCurrentUser()->first();

        $response = $this->call('PUT', '/api/accounts/'.$account->id, [
            'name' => 'numbat'
        ]);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('user_id', $content);
        $this->assertArrayHasKey('path', $content);
        $this->assertEquals('numbat', $content['name']);
    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_can_delete_an_account()
    {
        $user = $this->logInUser();

        $name = 'echidna';

        $account = new Account(compact('name'));
        $account->user()->associate($user);
        $account->save();

        $this->seeInDatabase('accounts', compact('name'));

        $response = $this->call('DELETE', '/api/accounts/'.$account->id);
        $this->assertEquals(204, $response->getStatusCode());
        $this->missingFromDatabase('accounts', compact('name'));

        // @TODO Test the 404 for the other methods as well (show, update)
        $response = $this->call('DELETE', '/api/accounts/0');
        $this->assertEquals(404, $response->getStatusCode());
    }
}
