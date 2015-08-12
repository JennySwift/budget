<?php namespace App\Totals;

use App\Models\Tag;
use Auth;
use Debugbar;

/**
 * Class BudgetTable
 * @package App\Tags
 */
class BudgetTable {

    /**
     * @var
     */
    public $tags;

    /**
     * @var array
     */
    public $totals;

    /**
     * @var
     */
    public $type;

    /**
     *
     */
    public function __construct()
    {

    }

    /**
     * @VP:
     * This seems to be causing 3 queries, not sure why.
     *
     * @param $user_id
     * @return mixed
     */
    public function getTagsWithFixedBudget()
    {
        //Middleware. Create my own middleware. But it won't work for GET requests.
        //Not checking logged in, checking session.
        checkLoggedIn();

        $tags = Tag::where('user_id', Auth::user()->id)
            ->where('flex_budget', null)
            ->whereNotNull('fixed_budget')
            ->orderBy('name', 'asc')
            ->get();

        return $tags;
    }

    /**
     *
     * @param $user_id
     * @return mixed
     */
    public function getTagsWithFlexBudget()
    {
        //Can use Auth::id() instead of Auth::user()->id
        $tags = Tag::where('user_id', Auth::user()->id)
            ->whereNotNull('flex_budget')
            ->orderBy('name', 'asc')
            ->get();

        return $tags;
    }

    /**
     * Get the numbers for the total row for either the fixed or flex budget table
     * @param $type
     * @return array
     */
    public function getTotalsForSpecifiedBudget()
    {
        //Budget factory would help a lot
        $totals = new BudgetTotalsRow(
            $this->getBudget(),
            $this->getSpentBeforeSD(),
            $this->getSpentAfterSD(),
            $this->getReceivedAfterSD()
        );

        return $totals;
    }

    /**
     * For the total A column in either the fixed or flex budget table.
     * @param $tags
     * @param $type
     * @return int
     */
    public function getBudget()
    {
        $total = 0;

        //I could do this in my tag model
        //This could be a constant in FixedBudget and FlexBudget class.
        //This method should (must?) be on my abstract budget class (FixedBudget or FlexBudget).
        $string = $this->type . '_budget';

        //This could be in TagsRepository
        //Foreach is slower on collections
        //Could use map (see codementor example). Foreach efficient if real array.
        foreach ($this->tags as $tag) {
            $total += $tag->$string;
        }

        return $total;
    }

    /**
     * For the total R column in either the fixed or flex budget table
     * @return int
     */
    public function getRemainingBudget()
    {
        $total = 0;

        foreach ($this->tags as $tag) {
            $total += $tag->remaining;
        }

        return $total;
    }

    /**
     * Get total expenses, either fixed or flex, after starting date
     * For the total '-' column in either the fixed or flex budget table.
     * @param $type
     * @return int
     */
    public function getSpentAfterSD()
    {
        $total = 0;

        foreach ($this->tags as $tag) {

            $total += $tag->spentAfterSD;
        }

        return $total;
    }

    /**
     * Get total expenses, either fixed or flex, before starting date.
     * For the total '-' column in either the fixed or flex budget table.
     * @return int
     */
    public function getSpentBeforeSD()
    {
        $total = 0;

        foreach ($this->tags as $tag) {
            $total += $tag->spentBeforeSD;
        }

        return $total;
    }

    /**
     * Get total received on after CSD, either fixed or flex.
     * For the total '+' column in either the fixed or flex budget table.
     * @return int
     */
    public function getReceivedAfterSD()
    {
        $total = 0;

        foreach ($this->tags as $tag) {
            $total += $tag->receivedAfterSD;
        }

        return $total;
    }

    /**
     * For the total C column in the fixed budget table
     * @return int
     */
    public function getCumulativeBudget()
    {
        $total = 0;

        foreach ($this->tags as $tag) {
            $total += $tag->cumulative;
        }

        return $total;
    }

}