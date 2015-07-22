<?php namespace App\Models;

use App\Repositories\Tags\TagsRepository;
use App\Services\BudgetTableTotalsService;
use App\Services\TotalsService;
use Illuminate\Database\Eloquent\Model;

class Total extends Model {

    /**
     *
     * @return array
     */
    public function getBudgetTotals()
    {
        $FB_info = $this->getBudgetInfo('fixed');
        $FLB_info = $this->getBudgetInfo('flex');

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
     * Get tags with $type budget (with each tag's totals), i.e, $tags.
     * Get totals for the totals row in the $type budget table, i.e, $totals.
     * @param $type
     * @return array
     */
    public function getBudgetInfo($type)
    {
        $tagsRepository = new TagsRepository();
        $tags = $tagsRepository->getTagsWithSpecifiedBudget($type);
        $budgetTableTotalsService = new BudgetTableTotalsService();

        $totals = [
            "budget" => $budgetTableTotalsService->getBudget($tags, $type),
            "spent" => $budgetTableTotalsService->getSpentAfterSD($tags),
            "received" => $budgetTableTotalsService->getReceivedAfterSD($tags),
            "spent_before_SD" => $budgetTableTotalsService->getSpentBeforeSD($tags)
        ];

        if ($type === 'fixed') {
            $totals['cumulative'] = $budgetTableTotalsService->getCumulativeBudget();
            $totals['remaining'] = $budgetTableTotalsService->getRemainingBudget($type);
        }

        return [
            'tags' => $tags,
            'totals' => $totals
        ];
    }

    /**
     * Get the user's remaining balance (RB), with EFLB in the formula.
     * @return int
     */
    public function getRBWithEFLB()
    {
//        $FB_totals = $this->getBudgetInfo('fixed')['totals'];
//        $FLB_totals = $this->getBudgetInfo('flex')['totals'];
        $budgetTableTotalsService = new BudgetTableTotalsService();
        $tagsRepository = new TagsRepository();
        $totalsService = new TotalsService();

        $RB =
              $totalsService->getCredit()
            - $budgetTableTotalsService->getRemainingBudget('fixed')
            + $totalsService->getEWB()
            + 0 //EFLB-not sure what it should be yet
            + $budgetTableTotalsService->getSpentBeforeSD($tagsRepository->getTagsWithSpecifiedBudget('fixed'))
            + $budgetTableTotalsService->getSpentBeforeSD($tagsRepository->getTagsWithSpecifiedBudget('flex'))
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
