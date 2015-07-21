<?php namespace App\Models;

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
    protected $appends = ['path', 'budget_type'];

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
        return route('tags.show', $this->id);
    }

    /**
     *
     * @return mixed
     */
    public static function getTags()
    {
        return Tag::where('user_id', Auth::user()->id)
            ->orderBy('name', 'asc')
            ->get();
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
    public function getCMN()
    {
        if ($this->starting_date) {
            $diff = Carbon::now()->diff(Carbon::createFromFormat('Y-m-d', $this->starting_date));

            $CMN = $diff->format('%y') * 12 + $diff->format('%m') + 1;
        }
        else {
            $CMN = 1;
        }

        return $this->CMN = $CMN;
    }

    /**
     *
     * @param $tag
     */
    public function setAllocationType($tag)
    {
        $allocated_fixed = $tag->pivot->allocated_fixed;
        $allocated_percent = $tag->pivot->allocated_percent;

        if (isset($allocated_fixed) && !$allocated_percent) {
            $tag->allocation_type = 'fixed';
        } elseif ($allocated_percent && !$allocated_fixed) {
            $tag->allocation_type = 'percent';
        }
    }

    /**
     * For one tag.
     * For getting the updated info after updating the allocation for that tag.
     * @param $transaction_id
     * @param $tag_id
     * @return mixed|null
     */
    public static function getAllocationInfo($transaction_id, $tag_id)
    {
        $transaction = Transaction::find($transaction_id);

        $tag = $transaction->tags()
            ->where('tag_id', $tag_id)
            ->first();

        $tag->setAllocationType($tag);

        return $tag;
    }

    /**
     * Get total spent on a given tag on or after starting date
     * @param $tag_id
     * @param $starting_date
     * @return mixed
     */
    public function getTotalSpent()
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

        return $this->spent = $total;
    }

    /**
     * Get total received on a given tag on or after starting date
     * @param $tag_id
     * @param $starting_date
     * @return mixed
     */
    public function getTotalReceived()
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

        return $this->received = $total;
    }

    /**
     * Get total spent on a given tag before starting date
     * @param $tag_id
     * @param $CSD
     * @return mixed
     */
    public function getTotalSpentBeforeCSD()
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

        return $this->spent_before_CSD = $total;
    }
}
