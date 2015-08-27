<?php namespace App\Models;

use App\Repositories\Tags\TagsRepository;
use App\Totals\RB;
use App\Totals\TotalsRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Tag
 * @package App\Models
 */
class Tag extends Model
{
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
        return route('api.tags.show', $this->id);
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
     * For tags with a flex budget.
     * This figure is dependant on what's left after fixed budget, savings,
     * and perhaps other figures are taken care of.
     * It is a percentage of what is left, hence the name 'flex' budget.
     * @return float
     */
//    public function getCalculatedBudgetAttribute()
//    {
////        $RB = new RB();
//        if ($this->budget_type === 'flex') {
//            return 3;
////            return $RB->withoutEFLB / 100 * $this->flex_budget;
//        }
//    }

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
     * Get the cumulative month number for a tag (CMN).
     * CMN is based on the starting date (CSD) for a tag.
     * @param $CSD
     * @return string
     */
    public function getCMNAttribute()
    {
        if ($this->starting_date) {
            $diff = Carbon::now()->diff(Carbon::createFromFormat('Y-m-d', $this->starting_date));

            $CMN = $diff->format('%y') * 12 + $diff->format('%m') + 1;
        }
        else {
            $CMN = 1;
        }

        return $CMN;
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

    /**
     * For one tag.
     * For getting the updated info after updating the allocation for that tag.
     * I'm now getting the info for all the transaction's tags (in the transaction model)
     * @param $transaction
     * @param $tag
     * @return mixed|null
     */
//    public function getAllocationInfo($transaction, $tag)
//    {
//        $tag = $transaction->tags()
//            ->where('tag_id', $tag->id)
//            ->first();
//
//        $tag->setAllocationType();
//
//        return $tag;
//    }

//    public function spentAfterSD()
//    {
//        return $this->hasOne('App/Models/Tag')
//            ->selectRaw('product_id, sum(available_stock) as aggregate');
//    }

    //Todo: The four methods below are causing a lot of queries to run.

    /**
     * Get total spent on a given tag on or after starting date
     * @param $tag_id
     * @param $starting_date
     * @return mixed
     */
    public function getSpentAfterSDAttribute()
    {
        $total = DB::table('transactions_tags')
            ->join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
            ->join('transactions', 'transactions_tags.transaction_id', '=', 'transactions.id')
            ->where('transactions_tags.tag_id', $this->id);

        if ($this->starting_date) {
            $total = $total->where('transactions.date', '>=', $this->starting_date);
        }

        $total = $total
            ->where('transactions.type', 'expense')
            ->sum('calculated_allocation');

        return $this->spentAfterSD = $total;
    }

    /**
     * Get total received on a given tag on or after starting date
     * @param $tag_id
     * @param $starting_date
     * @return mixed
     */
    public function getReceivedAfterSDAttribute()
    {
//        return 3;
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
     * @param $tag_id
     * @param $CSD
     * @return mixed
     */
    public function getSpentBeforeSDAttribute()
    {
//        return 3;


//        $total = Transaction::whereHas('tags', function($q)
//        {
//            $q->where('id', $this->id);
//        });







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
//        return 3;
        if ($this->budget_type === 'fixed') {
            return $this->remaining = $this->cumulative + $this->spentAfterSD + $this->receivedAfterSD;
        }
        elseif ($this->budget_type === 'flex') {
//            dd($this->spentAfterSD);
            return $this->remaining = $this->calculated_budget + $this->spentAfterSD + $this->receivedAfterSD;
        }
    }
}
