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
		$this->assertEquals(
			$this->baseUrl.'/auth/login',
			$response->getTargetUrl()
		);
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

		// This can be done by $this->visit('/') with Laravel 5.1
		$response = $this->call('GET', '/');
		$this->assertEquals(200, $response->getStatusCode());
	}

}
