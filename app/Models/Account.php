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
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
