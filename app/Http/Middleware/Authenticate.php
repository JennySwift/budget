<?php namespace App\Http\Middleware;

use App\Exceptions\NotLoggedInException;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;

class Authenticate {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ($this->auth->guest())
		{
			if (!($request->ajax() || $request->json()))
			{
				return redirect()->guest('auth/login');

			}
			throw new NotLoggedInException;
		}

		return $next($request);
	}

}
