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
        $user = $this->logInUser();

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
        $this->logInUser();

        $this->visit('/accounts')->see('account');
    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_can_display_the_budget_pages()
    {
        $this->logInUser();

        $this->visit('/budgets/fixed')->see('budget')->see('total');
        $this->visit('/budgets/flex')->see('budget')->see('total');
        $this->visit('/budgets/unassigned')->see('budget')->see('total');
    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_can_display_the_help_page()
    {
        $this->logInUser();

        $this->visit('/help')->see('help');
    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
    public function it_can_display_the_preferences_page()
    {
        $this->logInUser();

        $this->visit('/preferences')
            ->see('preferences')
            ->see('colours');
    }

}
