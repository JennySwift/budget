<?php namespace App\Models;

use App\Traits\ForCurrentUserTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Budget
 * @package App\Models
 */
class Budget extends Model
{

    use ForCurrentUserTrait;

    protected $fillable = ['type', 'name', 'amount', 'starting_date'];

    protected $appends = ['formattedStartingDate'];

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
     * @return mixed
     */
    public function transactions()
    {
        return $this->belongsToMany('App\Models\Transaction', 'budgets_transactions')
            ->withPivot('allocated_fixed', 'allocated_percent', 'calculated_allocation')
            ->orderBy('name', 'asc');
    }

    /**
     *
     * @return string
     */
    public function getFormattedStartingDateAttribute()
    {
        if ($this->starting_date) {
            return convertDate($this->starting_date, 'user');
        }
    }
}
