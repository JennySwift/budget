<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Preference
 * @package App\Models
 */
class Preference extends Model
{

    /**
     * @var array
     */
    protected $fillable = ['type', 'value'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
