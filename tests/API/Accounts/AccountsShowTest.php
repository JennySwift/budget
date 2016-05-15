<?php

use App\Models\Account;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class AccountsShowTest extends TestCase {

    use DatabaseTransactions;

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

//        $this->assertEquals($this->user->id, $content['user_id']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
