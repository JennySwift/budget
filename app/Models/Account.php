<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

/**
 * Class Account
 * @package App\Models
 */
class Account extends Model {

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    public static function getAccounts()
    {
        return Account::where('user_id', Auth::user()->id)
            ->orderBy('name', 'asc')
            ->get();
    }

}
