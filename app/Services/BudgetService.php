<?php

namespace App\Services;

use App\Models\Savings;
use App\Models\Tag;
use App\Models\Transaction;
use App\Repositories\Tags\TagsRepository;
use Illuminate\Support\Facades\DB;
use Auth;

/**
 * Class BudgetService
 * @package App\Services
 */
class BudgetService {

    /**
     * @var TagsRepository
     */
    protected $tagsRepository;

    protected $budgetTableTotalsService;

    protected $totalsService;

    /**
     * @param TagsRepository $tagsRepository
     */
    public function __construct(TagsRepository $tagsRepository, BudgetTableTotalsService $budgetTableTotalsService, TotalsService $totalsService)
    {
        $this->tagsRepository = $tagsRepository;
        $this->budgetTableTotalsService = $budgetTableTotalsService;
        $this->totalsService = $totalsService;
    }

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
     * @param $user_id
     * @param $type
     * @return array
     */
    public function getBudgetInfo($type)
    {
        $tags = $this->tagsRepository->getTagsWithSpecifiedBudget($type);

        $totals = [
            "budget" => $this->budgetTableTotalsService->getBudget($tags, $type),
            "spent" => $this->budgetTableTotalsService->getSpentAfterSD($tags),
            "received" => $this->budgetTableTotalsService->getReceivedAfterSD($tags),
            "spent_before_SD" => $this->budgetTableTotalsService->getSpentBeforeSD($tags)
        ];

        if ($type === 'fixed') {
            $totals['cumulative_budget'] = $this->budgetTableTotalsService->getCumulativeBudget();
            $totals['remaining'] = $this->budgetTableTotalsService->getRemainingBudget($type);
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
        $FB_totals = $this->getBudgetInfo('fixed')['totals'];
        $FLB_totals = $this->getBudgetInfo('flex')['totals'];

        $RB =
              $this->totalsService->getCredit()
            - $FB_totals['remaining']
            + $this->totalsService->getEWB()
            + 0 //EFLB-not sure what it should be yet
            + $FB_totals['spent_before_SD']
            + $FLB_totals['spent_before_SD']
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