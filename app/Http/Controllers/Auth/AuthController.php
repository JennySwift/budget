<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	protected $redirectTo = '/help';

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}

	/**
	 * From Valentin
	 * Handle a login request to the application.
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	// public function postLogin(Request $request)
	// {
	//     $this->validate($request, [
	//         'email'    => 'required|email',
	//         'password' => 'required',
	//     ]);

	//     $credentials = $request->only('email', 'password');
	    
	//     // Fetch the user by email
	//     $user = User::whereEmail($request->only('email'));

	//     if ($this->auth->validate($credentials)) {
	      
	//         if($user->isNotBlocked())
	//         {
	//             $this->auth->login($user);
	//             return redirect()->intended($this->redirectPath());
	//         }
	//     }
	    
	//     // Increment the throttle field
	//     $user->increments('attempts');

	//     return redirect($this->loginPath())
	//         ->withInput($request->only('email', 'remember'))
	//         ->withErrors([
	//             'email' => $this->getFailedLoginMessage(),
	//         ]);
	// }

}
