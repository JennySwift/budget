<?php

namespace App\Totals;

use App\Models\Savings;

class RB {

    public $withEFLB;
    public $withoutEFLB;
    public $FB_table;
    public $FLB_table;

    public function __construct(BudgetTable $FB_table, BudgetTable $FLB_table)
    {
        $this->FB_table = $FB_table;
        $this->FLB_table = $FLB_table;
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
        foreach ($this->FLB_table->tags as $tag) {
            $tag->calculated_budget = $this->withoutEFLB / 100 * $tag->flex_budget;
            $total+= $tag->calculated_budget;
        }

        $this->calculateUnallocatedRow();

        $total+= $this->FLB_table->unallocated['calculated_budget'];

        $this->FLB_table->totals['budget'] = '100.00';
        $this->FLB_table->totals['calculated_budget'] = $total;

        $this->calculateRemainingFLB();
    }

    public function calculateUnallocatedRow()
    {
        $unallocated_budget = 100 - $this->FLB_table->totals['budget'];

        $unallocated_row = [
            'budget' => $unallocated_budget,
            'calculated_budget' => $this->withoutEFLB / 100 * $unallocated_budget,
            'remaining' => $this->withoutEFLB / 100 * $unallocated_budget
        ];

        $this->FLB_table->unallocated = $unallocated_row;
    }

    public function calculateRemainingFLB()
    {
        $remaining = 0;
        foreach ($this->FLB_table->tags as $tag) {
            $remaining+= $tag->remaining;
        }
        $remaining+= $this->FLB_table->unallocated['remaining'];
        $this->FLB_table->totals['remaining'] = $remaining;
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
            - $this->FB_table->totals['remaining']
            + $basicTotals->getEWB()
            + $this->FLB_table->totals['spent_before_SD']
            + $this->FLB_table->totals['spent_after_SD']
            + $this->FB_table->totals['spent_before_SD']
            + $this->FB_table->totals['spent_after_SD']
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
        return $this->getRBWithEFLB() - $this->FLB_table->totals['spent_after_SD'];
    }
}