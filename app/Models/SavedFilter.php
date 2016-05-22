<?php

namespace App\Models;

use App\Traits\ForCurrentUserTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SavedFilter
 * @package App\Models
 */
class SavedFilter extends Model
{
    use ForCurrentUserTrait;

    /**
     * @var string
     */
    protected $table = 'filters';

    /**
     * @var array
     */
    protected $casts = ['filter' => 'json'];

    /**
     * @var array
     */
    protected $fillable = ['name', 'filter'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
