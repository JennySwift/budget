<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class AutocompleteTest
 */
class AutocompleteTest extends TestCase {

    use DatabaseTransactions;

    //todo

	/**
	 * A basic functional test example.
	 * @test
	 * @return void
	 */
//	public function it_lists_all_the_accounts()
//	{
//        $this->logInUser();
//
//        $accounts = Account::all();
//
//		$response = $this->call('GET', '/api/accounts');
//        $content = json_decode($response->getContent(), true);
//
//		$this->assertEquals(200, $response->getStatusCode());
//        $this->assertArrayHasKey('name', $content[0]);
//        $this->assertArrayHasKey('user_id', $content[0]);
//        $this->assertArrayHasKey('path', $content[0]);
//        $this->assertContains($accounts->first()->name, $content[0]);
//        $this->assertContains($accounts->get(1)->name, $content[1]);
//	}
}
