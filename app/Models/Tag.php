<?php namespace App\Models;

use App\Repositories\Tags\TagsRepository;
use App\Totals\RB;
use App\Totals\TotalsRepository;
use App\Traits\ForCurrentUserTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Tag
 * @package App\Models
 */
class Tag extends Model implements Arrayable
{

    use ForCurrentUserTrait;

    /**
     * @var array
     */
    protected $fillable = ['name', 'fixed_budget', 'flex_budget', 'starting_date', 'budget_id'];

    /**
     * @var array
     */
    protected $appends = ['path', 'budget_type', 'formatted_starting_date', 'CMN', 'cumulative'];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function transactions()
    {
        return $this->belongsToMany('App\Models\Transaction', 'transactions_tags');
    }

    public function sum()
    {
//        return $this->belongsToMany('App\Models\Transaction', 'transactions_tags')
//            ->selectRaw('calculated_allocation');
//            ->selectRaw('sum(calculated_allocation) as aggregate');
//            ->groupBy('tag_id');

//        $query = $this->belongsToMany('App\Models\Transaction', 'transactions_tags')
//            ->selectRaw('transactions_tags.calculated_allocation');
//        return $query;
//        dd($query->toSql());
//        return $this->transactions->sum('total');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function budget()
    {
        return $this->belongsTo('App\Models\Budget');
    }
    
    /**
     * Return the URL of the resource
     * @return string
     */
    public function getPathAttribute()
    {
        return route('tags.show', $this);
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

    /**
     *
     * @return mixed
     */
    public function getCumulativeAttribute()
    {
        if ($this->budget_type === 'fixed') {
            return $this->fixed_budget * $this->CMN;
        }
    }

    /**
     *
     * @return string
     */
    public function getBudgetTypeAttribute()
    {
        if ($this->fixed_budget) {
            return 'fixed';
        }
        elseif ($this->flex_budget) {
            return 'flex';
        }
        return;
    }

    /**
     *
     * @param $tag
     */
    public function setAllocationType()
    {
        if (isset($this->pivot->allocated_fixed) && !$this->pivot->allocated_percent) {
            $this->allocation_type = 'fixed';
        }
        elseif ($this->pivot->allocated_percent && !$this->pivot->allocated_fixed) {
            $this->allocation_type = 'percent';
        }
    }

    //Todo: The four methods below are causing a lot of queries to run.

    /**
     * Get total received on a given tag on or after starting date
     * @return mixed
     */
    public function getReceivedAfterSDAttribute()
    {
        $total = DB::table('transactions_tags')
            ->join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
            ->join('transactions', 'transactions_tags.transaction_id', '=', 'transactions.id')
            ->where('transactions_tags.tag_id', $this->id);

        if ($this->starting_date) {
            $total = $total->where('transactions.date', '>=', $this->starting_date);
        }

        $total = $total
            ->where('transactions.type', 'income')
            ->sum('calculated_allocation');

        return $this->receivedAfterSD = $total;
    }

    /**
     * Get total spent on a given tag before starting date
     * @return mixed
     */
    public function getSpentBeforeSDAttribute()
    {
        $total = DB::table('transactions_tags')
            ->join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
            ->join('transactions', 'transactions_tags.transaction_id', '=', 'transactions.id')
            ->where('transactions_tags.tag_id', $this->id);

        if ($this->starting_date) {
            $total = $total->where('transactions.date', '<', $this->starting_date);
        }

        $total = $total
            ->where('transactions.type', 'expense')
            ->sum('calculated_allocation');

        return $this->spentBeforeSD = $total;
    }

    /**
     * Get the remaining budget for tags with fixed or flex budget
     * @return mixed
     */
    public function getRemainingAttribute()
    {
        if ($this->budget_type === 'fixed') {
            return $this->remaining = $this->cumulative + $this->spentAfterSD + $this->receivedAfterSD;
        }
        elseif ($this->budget_type === 'flex') {
            return $this->remaining = $this->calculated_budget + $this->spentAfterSD + $this->receivedAfterSD;
        }
    }
}
