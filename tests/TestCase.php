<?php

use App\User;

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	protected $baseUrl = "http://localhost";

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';

		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		return $app;
	}

	/**
	 * Make an API call
	 * @param $method
	 * @param $uri
	 * @param array $parameters
	 * @param array $cookies
	 * @param array $files
	 * @param array $server
	 * @param null $content
	 * @return \Illuminate\Http\Response
	 */
	public function apiCall($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
	{
		$headers = $this->transformHeadersToServerVars([
			'Accept' => 'application/json'
		]);
		$server = array_merge($server, $headers);

		return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
	}

    public function logInUser()
    {
        $user = User::first();
        $this->be($user);
        return $user;
    }

}
