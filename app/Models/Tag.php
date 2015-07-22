<?php namespace App\Models;

use App\Repositories\Tags\TagsRepository;
use App\Services\BudgetService;
use App\Services\BudgetTableTotalsService;
use App\Services\TotalsService;
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
    protected $appends = ['path', 'budget_type', 'formatted_starting_date', 'CMN', 'remaining', 'cumulative', 'calculated_budget'];

    /**
     * @var
     */
    //    protected $budgetService;

    /**
     * The following caused this error:
     * Argument 1 passed to App\Models\Tag::__construct() must be an
     * instance of App\Services\BudgetService, none given
     */

    /**
     * @param BudgetService $budgetService
     */
//    public function __construct(BudgetService $budgetService) {
//        return $this->budgetService = $budgetService;
//    }

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
     * @return string
     */
    public function getFormattedStartingDateAttribute()
    {
        if ($this->starting_date) {
            return Transaction::convertDate($this->starting_date, 'user');
        }
    }

    /**
     * Get the remaining budget for tags with fixed budget
     * @return mixed
     */
    public function getRemainingAttribute()
    {
        if ($this->budget_type === 'fixed') {
            return $this->cumulative + $this->getSpentAfterSD() + $this->getReceivedAfterSD();
        }
        elseif ($this->budget_type === 'flex') {
            return $this->calculated_budget + $this->spent + $this->received;
        }
    }

    /**
     *
     * @return float
     */
    public function getCalculatedBudgetAttribute()
    {
        $total = new Total();
        if ($this->budget_type === 'flex') {
//            return 5;
            return $this->calculated_budget = $total->getRBWEFLB() / 100 * $this->flex_budget;
        }
    }

    /**
     * Get the remaining budget for tags with flex budget
     * @return mixed
     */
//    public function getRemainingBudget()
//    {
//        return $this->getCumulativeBudget() + $this->getTotalSpentAfterCSD() + $this->getTotalReceivedAfterCSD();
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

        $tag->setAllocationType();

        return $tag;
    }

    /**
     * Get total spent on a given tag on or after starting date
     * @param $tag_id
     * @param $starting_date
     * @return mixed
     */
    public function getSpentAfterSD()
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
    public function getReceivedAfterSD()
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
    public function getSpentBeforeSD()
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

        return $this->spent_before_SD = $total;
    }
}
