<?php

namespace App\Totals;

use App\Models\Savings;

class RB {

    public $withEFLB;
    public $withoutEFLB;
    public $FB;
    public $FLB;

    public function __construct(BudgetTable $FB, BudgetTable $FLB)
    {
        $this->FB = $FB;
        $this->FLB = $FLB;
        $this->withEFLB = 5;
        $this->withEFLB = $this->getRBWithEFLB();
        $this->withoutEFLB = $this->getRBWEFLB();

        //Now that we have RB, get the calculated budgets for tags with FLB
        $this->calculateBudgets();
    }

    /**
     * Now that we have RB, calculate the budget for each tag that has a FLB,
     * add the calculated_budget property to each tag,
     * and add each calculated_budget to show the total calculated budget in the total row
     */
    public function calculateBudgets()
    {
        $total = 0;
        foreach ($this->FLB->tags as $tag) {
            $tag->calculated_budget = $this->withoutEFLB / 100 * $tag->flex_budget;
            $total+= $tag->calculated_budget;
        }

        $this->calculateUnallocatedRow();

        $total+= $this->FLB->unallocated['calculated_budget'];
        $this->FLB->totals->calculated_budget = $total;

//        dd($total);

        $this->FLB->totals->budget = '100.00';

        $this->calculateRemainingFLB();
    }

    public function calculateUnallocatedRow()
    {
        $unallocated_budget = 100 - $this->FLB->totals->budget;

        $unallocated_row = [
            'budget' => $unallocated_budget,
            'calculated_budget' => $this->withoutEFLB / 100 * $unallocated_budget,
            'remaining' => $this->withoutEFLB / 100 * $unallocated_budget
        ];

        $this->FLB->unallocated = $unallocated_row;
    }

    public function calculateRemainingFLB()
    {
        $remaining = 0;
        foreach ($this->FLB->tags as $tag) {
            $remaining+= $tag->remaining;
        }

        $remaining+= $this->FLB->unallocated['remaining'];
        $this->FLB->totals->remaining = $remaining;
    }

    /**
     * Get the user's remaining balance (RB), with EFLB in the formula.
     * Still figuring out the formula and if this is the figure we want.
     * @return int
     */
    public function getRBWithEFLB()
    {
//        $budgetTableTotalsService = new BudgetTableTotalsService($this);
//        $tagsRepository = new TagsRepository();
        //If totalsservice is calling this file, this file should not call TotalsService (unless tightly coupled, but rare)
        //(put the getCredit method in this file)
        //maybe interface if two repositories have similar methods?
        //flex budget repository and fixed budget repository and they would share same methods, interface budget
        //or extend budgetrepository
        $basicTotals = new BasicTotals();

        $RB =
            $basicTotals->getCredit()
            - $this->FB->totals->remaining
            + $basicTotals->getEWB()
            + $this->FLB->totals->spentBeforeSD
            + $this->FLB->totals->spentAfterSD
            + $this->FB->totals->spentBeforeSD
            + $this->FB->totals->spentAfterSD
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
        return $this->getRBWithEFLB() - $this->FLB->totals->spentAfterSD;
    }
}