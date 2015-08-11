<?php namespace App\Totals;

/**
 * Class FixedAndFlexData
 * @package App\Totals
 */
/**
 * Class FixedAndFlexData
 * @package App\Totals
 */
class FixedAndFlexData
{

    /**
     * @var
     */
    public $FB;

    /**
     * @var
     */
    public $FLB;

    /**
     * @var
     */
    public $RB;

    /**
     * @var
     */
    public $RBWEFLB;

    /**
     * Get all the data for the fixed and flex budget tables,
     * as well as RB and RBWEFLB.
     * Todo: In the future I could also create interfaces.
     * @return array
     */
    public function __construct()
    {
//        $this->FB = new BudgetTable('fixed');
        $this->FB = new FixedBudgetTable();
//        $this->FLB = new BudgetTable('flex');
        $this->FLB = new FlexBudgetTable();

        $RB = new RB($this->FB, $this->FLB);
        $this->RB = number_format($RB->withEFLB, 2);
        $this->RBWEFLB = number_format($RB->withoutEFLB, 2);

        $this->formatTotals();
    }

    private function formatTotals()
    {
        $this->formatFBTags();

        $this->formatFLBTags();
//        var_dump($this->FB->totals);

        $this->FB->totals = numberFormatObject($this->FB->totals);
        $this->FLB->totals = numberFormatObject($this->FLB->totals);



        $this->FLB->unallocated = numberFormat($this->FLB->unallocated);
    }

    /**
     *
     * @return mixed
     */
    private function formatFBTags()
    {
        foreach ($this->FB->tags as $tag) {
            $tag->fixed_budget = number_format($tag->fixed_budget, 2);
            //This isn't working
//            dd(number_format($tag->cumulative, 2));
            $tag->cumulative = number_format($tag->cumulative, 2);
//            dd($tag->cumulative);
            $tag->spentBeforeSD = number_format($tag->spentBeforeSD, 2);
            //not working
            $tag->spentAfterSD = number_format($tag->spentAfterSD, 2);
            //not working
            $tag->receivedAfterSD = number_format($tag->receivedAfterSD, 2);
            //This isn't working
            $tag->remaining = number_format($tag->remaining, 2);
        }
    }

    private function formatFLBTags()
    {
        foreach ($this->FLB->tags as $tag) {
            $tag->flex_budget = number_format($tag->flex_budget, 2);
            $tag->calculated_budget = number_format($tag->calculated_budget, 2);
            //These three aren't working
            $tag->spentAfterSD = number_format($tag->spentAfterSD, 2);
            $tag->receivedAfterSD = number_format($tag->receivedAfterSD, 2);
            $tag->remaining = number_format($tag->remaining, 2);
        }
    }
}