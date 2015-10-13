<?php namespace App;

use App\Models\Preference;
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
	protected $fillable = ['name', 'email', 'password', 'preferences'];

    /**
     * @var array
     */
    protected $appends = ['gravatar', 'path'];

    /**
     * @var array
     */
    protected $casts = ['preferences' => 'json'];

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
    public function favouriteTransactions()
    {
        return $this->hasMany('App\Models\FavouriteTransaction');
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
    public function filters()
    {
        return $this->hasMany('App\Models\SavedFilter');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fixedBudgets()
    {
        return $this->hasMany('App\Models\Budget')->whereType('fixed');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function flexBudgets()
    {
        return $this->hasMany('App\Models\Budget')->whereType('flex');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
//    public function colors()
//    {
//        return $this->hasMany('App\Models\Color');
//    }

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
        return route('api.user.show', $this->id);
    }

    /**
     *
     * @return Preference
     */
    public function preferences()
    {
        return new Preference($this);
    }

    /**
     *
     */
    public static function insertRowsForNewUser()
	{
		//todo
	}

}
