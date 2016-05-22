<?php

use App\Models\Account;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class AccountsDestroyTest extends TestCase {

    use DatabaseTransactions;
    
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
