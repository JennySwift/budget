<?php

use App\User;

class PagesTest extends TestCase {

	/**
	 * A basic functional test example.
	 * @test
	 * @return void
	 */
	public function it_redirects_the_user_if_not_authenticated()
	{
		$response = $this->call('GET', '/');

		$this->assertEquals(302, $response->getStatusCode());
		$this->assertTrue($response->isRedirection());
        $this->assertRedirectedTo($this->baseUrl.'/auth/login');
	}

	/**
	 * A basic functional test example.
	 * @test
	 * @return void
	 */
	public function it_can_display_the_homepage()
	{
		$user = User::first();
		$this->be($user);

        $transaction = \App\Models\Transaction::first();

		$this->visit('/')
             ->see($user->name)
             ->see($transaction->account->name);
	}

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_can_display_the_accounts_page()
    {
        $user = User::first();
        $this->be($user);

        $this->visit('/accounts')->see('account');
    }

}
