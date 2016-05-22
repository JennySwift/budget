<?php

use App\Models\Account;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class AccountsIndexTest extends TestCase {

    use DatabaseTransactions;

    /**
     * @todo: check the accounts belong to the user
     * @test
     * @return void
     */
    public function it_gets_the_accounts_with_their_balances()
    {
        $this->logInUser();
        $data = [
            'includeBalance' => true
        ];
        $response = $this->call('GET', '/api/accounts', $data);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkAccountKeysExist($content[0]);

        $this->assertEquals('bank account', $content[0]['name']);
        $this->assertEquals('1100', $content[0]['balance']);

        $this->assertEquals('cash', $content[1]['name']);
        $this->assertEquals('1090', $content[1]['balance']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @todo: check the accounts belong to the user
     * @test
     * @return void
     */
    public function it_gets_the_accounts_without_their_balances()
    {
        $this->logInUser();
        $data = [
//            'includeBalance' => true
        ];
        $response = $this->call('GET', '/api/accounts', $data);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkAccountKeysExist($content[0]);

        $this->assertEquals('bank account', $content[0]['name']);
        $this->assertArrayNotHasKey('balance', $content[0]);

        $this->assertEquals('cash', $content[1]['name']);
        $this->assertArrayNotHasKey('balance', $content[1]);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
