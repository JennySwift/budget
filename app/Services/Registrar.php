<?php namespace App\Services;

use App\User;
use App\Color;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;
use Log;

class Registrar implements RegistrarContract {

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
			'password' => 'required|confirmed|min:6',
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

}

// my attempt at custom validation

Validator::extend('accepted_email', function ($attribute, $value, $parameters) {
	$accepted_emails = [
		//enter emails here
		'cheezyspaghetti@gmail.com',
        'nihantanu@gmail.com'
	];
	$is_accepted = in_array($value, $accepted_emails);
	return $is_accepted;
});
