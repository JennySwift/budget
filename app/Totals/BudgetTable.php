<?php namespace App\Totals;

use App\Models\Tag;
use Auth;

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
     * @param $type
     */
    public function __construct($type)
    {
        $this->type = $type;

        if ($type === 'fixed') {
            $this->tags = $this->getTagsWithFixedBudget();
        }
        elseif ($type === 'flex') {
            $this->tags = $this->getTagsWithFlexBudget();
        }

        $this->totals = $this->getTotalsForSpecifiedBudget();
    }

    /**
     * @VP:
     * This seems to be causing 3 queries, not sure why.
     * @param $user_id
     * @return mixed
     */
    public function getTagsWithFixedBudget()
    {
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
        $totals = new BudgetTotalsRow(
            $this->getBudget(),
            $this->getSpentBeforeSD(),
            $this->getSpentAfterSD(),
            $this->getReceivedAfterSD()
        );

        if ($this->type === 'fixed') {
            $totals->remaining = $this->getRemainingBudget();
            $totals->cumulative = $this->getCumulativeBudget();
        }

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
        $string = $this->type . '_budget';

        //This could be in TagsRepository
        //Foreach is slower on collections
        //Could use map (see codementor example). Foreach efficient if real array.
        foreach ($this->tags as $tag) {
            $total += $tag->$string;
        }

        if ($this->type === 'fixed') {
            $this->fixedBudget = $total;
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
//            dd($tag);
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
//            dd($tag);

            /**
             * @VP:
             * Question 1:
             * Can I get the 'spentAfterSD' property here without running the query again?
             * (The query in the getSpentAfterSDAttribute method on the tag model.)
             * It is an appended attribute (at the time of writing),
             * and I thought maybe it could just get it the first time and then remember it
             * when I need to access the property like this, without running the query again.
             *
             * $total += $tag->spentAfterSD;
             *
             * Question 2:
             * I have now removed the 'spentAfterSD' attribute from $appends on my tag model,
             * because my appended attributes were running a lot of queries.
             * But why is the above line not adding the property to the tag?
             */
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