<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * From Valentin:
	 * "attempts" is the name of the field that you will increment on each login attempt
	 * this method will return true if the number of attempts is lower than 5
	 * false otherwise :)
	 */ 
	// public function isNotBlocked()
	// {
	//     return $this->attempts > 5;
	// }

	/**
	 * functions
	 */
	
	public static function insertRowsForNewUser()
	{
		Color::create([
			'item' => 'income',
			'color' => '#017d00',
			'user_id' => Auth::user()->id
		]);
		Color::create([
			'item' => 'expense',
			'color' => '#fb5e52',
			'user_id' => Auth::user()->id
		]);
		Color::create([
			'item' => 'transfer',
			'color' => '#fca700',
			'user_id' => Auth::user()->id
		]);	
	}

}
