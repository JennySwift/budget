<?php namespace App;

use App\Models\Setting;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Auth;

/**
 * Class User
 * @package App
 */
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
	protected $fillable = ['name', 'email', 'password', 'settings'];

    /**
     * @var array
     */
    protected $appends = ['gravatar', 'path'];

    /**
     * @var array
     */
    protected $casts = ['settings' => 'json'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany('App\Models\Account');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function colors()
    {
        return $this->hasMany('App\Models\Color');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function savings()
    {
        return $this->hasOne('App\Models\Savings');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->hasMany('App\Models\Tag');
    }

    /**
     * Return the gravatar URL for the user
     * @return string
     */
    public function getGravatarAttribute()
    {
        $email = md5($this->email);

        return "https://secure.gravatar.com/avatar/{$email}?s=37&r=g&default=mm";
    }
    
    /**
     * Return the URL of the resource
     * @return string
     */
    public function getPathAttribute()
    {
        return route('user.show', $this->id);
    }

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
     *
     * @return Setting
     */
    public function settings()
    {
        return new Setting($this);
    }

    /**
     *
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
