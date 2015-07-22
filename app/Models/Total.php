<?php namespace App\Models;

use App\Repositories\Tags\TagsRepository;
use App\Services\BudgetTableTotalsService;
use App\Services\TotalsService;
use Illuminate\Database\Eloquent\Model;

class Total extends Model {

    /**
     * This is the method that calls the other total stuff
     * @return array
     */
    public function getFixedAndFlexData()
    {
        $FB_info = $this->getTagsAndTotals('fixed');
        $FLB_info = $this->getTagsAndTotals('flex');

//        //Get the unallocated values for flex budget
//        $FLB_info['unallocated'] = $this->budgetTableTotalsService->getUnallocatedFLB($FLB_info, $RBWEFLB);
//
//        //Add the unallocated budget to the total budget so it equals 100%
//        $FLB_info['totals']['budget']+= $FLB_info['unallocated']['budget'];
//
//        //Add the unallocated calculated budget to the total budget
//        $total_calculated_budget+= $FLB_info['unallocated']['calculated_budget'];
//
//        //Add the unallocated remaining budget to the total budget
//        $total_remaining+= $FLB_info['unallocated']['remaining'];

        return [
            "FB" => $FB_info,
            "FLB" => $FLB_info,
            "RB" => number_format($this->getRBWithEFLB(), 2),
            "RBWEFLB" => number_format($this->getRBWEFLB(), 2)
        ];
    }

    /**
     * For either the fixed of flex budget table.
     * Get tags with $type budget (with each tag's totals), i.e, $tags.
     * Get totals for the totals row in the $type budget table, i.e, $totals.
     * @param $type
     * @return array
     */
    public function getTagsAndTotals($type)
    {
        return [
            'tags' => $this->getTags($type),
            'totals' => $this->getTotals($type)
        ];
    }

    /**
     * Get the tags (with their totals) for either the fixed or flex budget table
     * @param $type
     * @return mixed
     */
    public function getTags($type)
    {
        $tagsRepository = new TagsRepository();
        return $tagsRepository->getTagsWithSpecifiedBudget($type);
    }


    /**
     * Get the numbers for the total row for either the fixed or flex budget table
     * @param $type
     * @return array
     */
    public function getTotals($type)
    {
        $budgetTableTotalsService = new BudgetTableTotalsService();

        $totals = [
            "budget" => $budgetTableTotalsService->getBudget($type),
            "spent" => $budgetTableTotalsService->getSpentAfterSD($type),
            "received" => $budgetTableTotalsService->getReceivedAfterSD($type),
            "spent_before_SD" => $budgetTableTotalsService->getSpentBeforeSD($type)
        ];

        if ($type === 'fixed') {
            $totals['cumulative'] = $budgetTableTotalsService->getCumulativeBudget();
            $totals['remaining'] = $budgetTableTotalsService->getRemainingBudget($type);
        }

        return $totals;
    }

    /**
     * Get the user's remaining balance (RB), with EFLB in the formula.
     * @return int
     */
    public function getRBWithEFLB()
    {
        $budgetTableTotalsService = new BudgetTableTotalsService();
        $tagsRepository = new TagsRepository();
        $totalsService = new TotalsService();

        $RB =
              $totalsService->getCredit()
            - $budgetTableTotalsService->getRemainingBudget('fixed')
            + $totalsService->getEWB()
            + $budgetTableTotalsService->getSpentAfterSD('flex')
            + $budgetTableTotalsService->getSpentBeforeSD('fixed')
            + $budgetTableTotalsService->getSpentBeforeSD('flex')
            - Savings::getSavingsTotal();

        return $RB;
    }

    /**
     * Get remaining balance without the expenses with flex budget
     * @return int
     */
    public function getRBWEFLB()
    {
        $EFLB = 0;
        return $this->getRBWithEFLB() - $EFLB;
    }


}
