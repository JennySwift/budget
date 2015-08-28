<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Budget
 * @package App\Models
 */
class Budget extends Model
{
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
    public function tags()
    {
        return $this->hasMany('App\Models\Tag');
    }
}
