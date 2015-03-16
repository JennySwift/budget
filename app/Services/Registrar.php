<?php namespace App\Services;

use App\User;
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
		'cheezyspaghetti@optusnet.com.au',
		'cheezyspaghetti@gmail.com',
		'jennyswiftsblog@gmail.com',
		'peter.c.swift@gmail.com',
		'swifties1@me.com',
		'bernadettebab@gmail.com'
	];
	$is_accepted = in_array($value, $accepted_emails);
	// Log::info('value: ' . $value);
	// Log::info('attribute: ' . $attribute);
	// Log::info('parameters', $parameters);
	// Log::info('accepted_emails', $accepted_emails);
	// Log::info('is_accepted: ' . $is_accepted);
	return $is_accepted;
});

// Validator::resolver(function($translator, $data, $rules, $messages)
// {
//     return new CustomValidator($translator, $data, $rules, $messages);
// });

// Class 'App\Services\CustomValidator' not found
