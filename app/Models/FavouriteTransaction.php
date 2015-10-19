<?php

namespace App\Models;

use App\Traits\ForCurrentUserTrait;
use Illuminate\Database\Eloquent\Model;

class FavouriteTransaction extends Model
{
    use ForCurrentUserTrait;

    protected $table = 'favourite_transactions';

    protected $fillable = ['name', 'description', 'merchant', 'total', 'type'];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }

    /**
     * Get budgets for one transaction, assigned or unassigned
     * @return $this
     */
    public function budgets()
    {
        return $this->belongsToMany('App\Models\Budget', 'budgets_favourite_transactions')
            ->orderBy('name', 'asc');
    }
}
