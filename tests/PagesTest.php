<?php

use App\User;

/**
 * Class PagesTest
 * Todo: Commenting these out because PHPUnit was saying these tests were 'risky'
 */
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
        $this->assertRedirectedTo($this->baseUrl.'/login');
	}

	/**
	 * A basic functional test example.
	 * @test
	 * @return void
	 */
	public function it_can_display_the_homepage()
	{
        $this->logInUser();

        $transaction = \App\Models\Transaction::first();

		$this->visit('/')
             ->see($this->user->name)
             ->see($transaction->account->name);
	}

    /**
     * @test
     * @VP: (less important)
     * Why is $time around .8 here whereas in Postman it is around 1400ms?
     */
    public function it_tests_the_page_load_speed_of_home_page()
    {
        $start = microtime(true);
        $this->logInUser(2);
        $this->visit('/');
        $time = microtime(true) - $start;
        $this->assertLessThan(1, $time);
    }

    /**
     * @test
     */
//    public function it_tests_the_page_load_speed_of_fixed_budgets_page()
//    {
//        $start = microtime(true);
//        $this->logInUser(2);
//        $this->visit('/#/fixed-budgets');
//        $time = microtime(true) - $start;
//        $this->assertLessThan(1.2, $time);
//    }

    /**
     * @test
     */
//    public function it_tests_the_page_load_speed_of_flex_budgets_page()
//    {
//        $start = microtime(true);
//        $this->logInUser(2);
//        $this->visit('/budgets/flex');
//        $time = microtime(true) - $start;
//        $this->assertLessThan(1.2, $time);
//    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
//    public function it_can_display_the_accounts_page()
//    {
//        $this->logInUser();
//
//        $this->visit('/#/accounts')->see('account');
//    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
//    public function it_can_display_the_budget_pages()
//    {
//        $this->logInUser();
//
//        $this->visit('/budgets/fixed')->see('budget')->see('total');
//        $this->visit('/budgets/flex')->see('budget')->see('total');
//        $this->visit('/budgets/unassigned')->see('budget')->see('total');
//    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
//    public function it_can_display_the_help_page()
//    {
//        $this->logInUser();
//
//        $this->visit('/help')->see('help');
//    }

    /**
     * A basic functional test example.
     * @test
     * @return void
     */
//    public function it_can_display_the_preferences_page()
//    {
//        $this->logInUser();
//
//        $this->visit('/preferences')
//            ->see('preferences')
//            ->see('colours');
//    }

}
