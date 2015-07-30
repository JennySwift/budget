<?php namespace App\Totals;

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
     * Todo: Add the unallocated row back in and the number formatting
     * @return array
     */
    public function __construct()
    {
        $this->FB = new BudgetTable('fixed');
        $this->FLB = new BudgetTable('flex');

        $RB = new RB($this->FB, $this->FLB);
        $this->RB = $RB->withEFLB;
        $this->RBWEFLB = $RB->withoutEFLB;
    }

    /**
     * Get all the data for the fixed and flex budget tables,
     * as well as RB and RBWEFLB.
     * This is the method that calls the other total stuff.
     * Todo: Could be fixedandflexdata model with 4 properties (the ones I am returning here)
     * In the future I could also create interfaces.
     * Like example in codementor. Don't use extends model and line 6 here because I don't need the model method things.
     * @return array
     */

//    public function getFixedAndFlexData()
//    {
//        $this->FB_info = new BudgetTable('fixed');
//        $this->FLB_info = new BudgetTable('flex');
//
//        $RB = new RB();
//
//        //Get the unallocated values for flex budget
////        $this->FLB_info['unallocated'] = $FLB_info->totals->unallocated;
////        $this->FLB_info['totals']['budget'] = 100;
//
//        return [
//            "FB" => $this->FB_info,
//            "FLB" => $this->FLB_info,
//            "RB" => number_format($RB->withEFLB, 2),
//            "RBWEFLB" => number_format($RB->withoutEFLB, 2)
//        ];
//    }

    /**
     * For either the fixed of flex budget table.
     * Get tags with $type budget (with each tag's totals), i.e, $tags.
     * Get totals for the totals row in the $type budget table, i.e, $totals.
     * @param $type
     * @return array
     */
//    public function getTagsAndTotalsForSpecifiedBudget($type)
//    {
//        $budgetTable = new BudgetTable($type);
//        $tags = $budgetTable->tags;
//        $budgetTableTotalsService = new BudgetTableTotalsService($type, $tags);
//
//        return [
//            'tags' => $tags,
//            'totals' => $budgetTableTotalsService->getTotalsForSpecifiedBudget()
//        ];
//    }
}