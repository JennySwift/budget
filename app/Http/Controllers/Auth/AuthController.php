<?php namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
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
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'getLogout']);
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users|accepted_email',
			'password' => 'required|confirmed|min:10',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		return User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
		]);
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
