<?php namespace App\Repositories\Totals;

use App\Models\FixedAndFlexData;
use App\Models\Savings;
use App\Repositories\Tags\TagsRepository;
use App\Services\BudgetTableTotalsService;
use App\Services\TotalsService;

/**
 * Class TotalsRepository
 * @package App\Repositories\Totals
 */
class TotalsRepository {

    /**
     * For either the fixed of flex budget table.
     * Get tags with $type budget (with each tag's totals), i.e, $tags.
     * Get totals for the totals row in the $type budget table, i.e, $totals.
     * @param $type
     * @return array
     */
    public function getTagsAndTotalsForSpecifiedBudget($type)
    {
        return [
            'tags' => $this->getTagsForSpecifiedBudget($type),
            'totals' => $this->getTotalsForSpecifiedBudget($type)
        ];
    }

    /**
     * Get the tags (with their totals) for either the fixed or flex budget table
     * @param $type
     * @return mixed
     */
    public function getTagsForSpecifiedBudget($type)
    {
        $tagsRepository = new TagsRepository();
        return $tagsRepository->getTagsWithSpecifiedBudget($type);
    }


    /**
     * Get the numbers for the total row for either the fixed or flex budget table
     * @param $type
     * @return array
     */
    public function getTotalsForSpecifiedBudget($type)
    {
        $budgetTableTotalsService = new BudgetTableTotalsService();

        $totals = [
            "budget" => $budgetTableTotalsService->getBudget($type),
            "spent_after_SD" => $budgetTableTotalsService->getSpentAfterSD($type),
            "received_after_SD" => $budgetTableTotalsService->getReceivedAfterSD($type),
            "spent_before_SD" => $budgetTableTotalsService->getSpentBeforeSD($type),
            "remaining" => $budgetTableTotalsService->getRemainingBudget($type)
        ];

        if ($type === 'fixed') {
            $totals['cumulative'] = $budgetTableTotalsService->getCumulativeBudget();
        }

        else {
            $totals['calculated_budget'] = $budgetTableTotalsService->getCalculatedBudget();
        }

        $totals = numberFormat($totals);

        return $totals;
    }

    /**
     * Get the user's remaining balance (RB), with EFLB in the formula.
     * Still figuring out the formula and if this is the figure we want.
     * @return int
     */
    public function getRBWithEFLB()
    {
        $budgetTableTotalsService = new BudgetTableTotalsService();
        $tagsRepository = new TagsRepository();
        //If totalsservice is calling this file, this file should not call TotalsService (unless tightly coupled, but rare)
        //(put the getCredit method in this file)
        //maybe interface if two repositories have similar methods?
        //flex budget repository and fixed budget repository and they would share same methods, interface budget
        //or extend budgetrepository
        $fixedAndFlexData = new FixedAndFlexData($this);
        $totalsService = new TotalsService($fixedAndFlexData);

        $RB =
            $totalsService->getCredit()
            - $budgetTableTotalsService->getRemainingBudget('fixed')
            + $totalsService->getEWB()
            + $budgetTableTotalsService->getSpentBeforeSD('flex')
            + $budgetTableTotalsService->getSpentAfterSD('flex')
            + $budgetTableTotalsService->getSpentBeforeSD('fixed')
            + $budgetTableTotalsService->getSpentAfterSD('fixed')
            - Savings::getSavingsTotal();

        return $RB;
    }

    /**
     * Get remaining balance without the expenses with flex budget.
     * Still figuring out the formula and if this is the figure we want.
     * @return int
     */
    public function getRBWEFLB()
    {
        $budgetTableTotalsService = new BudgetTableTotalsService();
        return $this->getRBWithEFLB() - $budgetTableTotalsService->getSpentAfterSD('flex');
    }
}