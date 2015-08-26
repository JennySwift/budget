<?php

namespace App\Models\Totals;

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
//    public function __construct()
//    {
//        $this->FB = new FixedBudgetTable();
//        $this->FLB = new FlexBudgetTable();
//
//        $RB = new RB($this->FB, $this->FLB);
//        $this->RB = number_format($RB->withEFLB, 2);
//        $this->RBWEFLB = number_format($RB->withoutEFLB, 2);
//
////        $this->formatTotals();
//    }

    /**
     *
     * @return static
     */
    static public function createFromDatabase()
    {
        $object = new static;
        $object->FB = new FixedBudgetTable();
        $object->FLB = new FlexBudgetTable();

        $RB = new RB($object->FB, $object->FLB);
        $object->RB = number_format($RB->withEFLB, 2);
        $object->RBWEFLB = number_format($RB->withoutEFLB, 2);

        // return new FixedAndFlexData();
        return $object;
    }

    /**
     * Cast the object into an array
     * @return array
     */
    public function toArray()
    {
        return [
            'FB' => $this->FB,
            'FLB' => $this->FLB,
            'RB' => $this->RB,
            'RBWEFLB' => $this->RBWEFLB
        ];
    }

    private function formatTotals()
    {
//        $this->formatFBTags();
//        $this->formatFLBTags();

//        $this->FB->totals = numberFormatObject($this->FB->totals);
//        $this->FLB->totals = numberFormatObject($this->FLB->totals);
//
//        $this->FLB->unallocated = numberFormat($this->FLB->unallocated);
    }

    /**
     *
     * @return mixed
     */
    private function formatFBTags()
    {
        //Use map method on collection instead of loop.
        //I could get rid of these format methods by having a tag presenter.
        //If I don't use tag presenter the formatted methods could be on the tag model.
        //But actually this should be the job of the JS.
        //Accounting.js to put comma between thousands.
        //Number formatting can be done in PHP if using Blade rendering.
        //Or if returning object with both raw and formatted values (with Transfomer, see Codementor example)
        //Both methods acceptable, perhaps the latter is preferable.
//        foreach ($this->FB->tags as $tag) {
//            $tag->fixed_budget = number_format($tag->fixed_budget, 2);
//            //This isn't working. Check if it's a string or integer.
////            dd(number_format($tag->cumulative, 2));
//            $tag->cumulative = number_format($tag->cumulative, 2);
////            dd($tag->cumulative);
//            $tag->spentBeforeSD = number_format($tag->spentBeforeSD, 2);
//            //not working
//            $tag->spentAfterSD = number_format($tag->spentAfterSD, 2);
//            //not working
//            $tag->receivedAfterSD = number_format($tag->receivedAfterSD, 2);
//            //This isn't working
//            $tag->remaining = number_format($tag->remaining, 2);
//        }
    }

//    private function formatFLBTags()
//    {
//        foreach ($this->FLB->tags as $tag) {
//            $tag->flex_budget = number_format($tag->flex_budget, 2);
//            $tag->calculated_budget = number_format($tag->calculated_budget, 2);
//            //These three aren't working
//            $tag->spentAfterSD = number_format($tag->spentAfterSD, 2);
//            $tag->receivedAfterSD = number_format($tag->receivedAfterSD, 2);
//            $tag->remaining = number_format($tag->remaining, 2);
//        }
//    }
}