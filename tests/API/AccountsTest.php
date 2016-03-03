<?php

use App\Models\Account;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class AccountsTest extends TestCase {

    use DatabaseTransactions;

	/**
	 * @test
	 * @return void
	 */
	public function it_lists_all_the_accounts()
	{
        $this->logInUser();

        $accounts = Account::all();

		$response = $this->call('GET', '/api/accounts');
        $content = json_decode($response->getContent(), true);

        $this->checkAccountKeysExist($content[0]);

        $this->assertContains($accounts->first()->name, $content[0]);
        $this->assertContains($accounts->get(1)->name, $content[1]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
	}

    /**
     * @todo: check the accounts belong to the user
     * @test
     * @return void
     */
    public function it_gets_the_accounts()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/accounts');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkAccountKeysExist($content[0]);

        $this->assertEquals('bank account', $content[0]['name']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_displays_an_account()
    {
        $this->logInUser();

        $account = Account::forCurrentUser()->first();

        $response = $this->call('GET', '/api/accounts/'.$account->id);
        $content = json_decode($response->getContent(), true);

        $this->checkAccountKeysExist($content);

        $this->assertEquals($this->user->id, $content['user_id']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

	/**
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

        $this->checkAccountKeysExist($content);

        $this->assertEquals('kangaroo', $content['name']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
	}

    /**
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

        $this->checkAccountKeysExist($content);

        $this->assertEquals('numbat', $content['name']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_delete_an_account()
    {
        $this->logInUser();

        $name = 'echidna';

        $account = new Account(compact('name'));
        $account->user()->associate($this->user);
        $account->save();

        $response = $this->call('DELETE', '/api/accounts/'.$account->id);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->call('DELETE', '/api/account/' . $account->id);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
