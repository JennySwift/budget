<?php

use App\Models\Account;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class AccountsStoreTest extends TestCase {

    use DatabaseTransactions;

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
}
