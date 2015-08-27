<?php namespace App\Models;

use App\Traits\ForCurrentUserTrait;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

/**
 * Class Account
 * @package App\Models
 */
class Account extends Model {

    use ForCurrentUserTrait;

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @var array
     */
    protected $appends = ['path'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }
    
    /**
     * Return the URL of the resource
     * @return string
     */
    public function getPathAttribute()
    {
        return route('api.accounts.show', $this->id);
    }

    /**
     *
     * @return mixed
     */
    public static function getAccounts()
    {
        return Account::where('user_id', Auth::user()->id)
            ->orderBy('name', 'asc')
            ->get();
    }

}
