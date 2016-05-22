<?php

use App\Models\Account;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class AccountsUpdateTest extends TestCase {

    use DatabaseTransactions;

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
}
