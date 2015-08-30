<?php

namespace App\Models\Totals;

/**
 * Class RB
 * @package App\Totals
 * @TODO Take a look at the calculate... methods and think about the responsability of the RB object
 * Does this line of code belongs on the RB object? Are you setting a property on a property of a property of an object?
 * See @complex
 * And try to use proper naming (trust me, it is worth it!!!!! :))
 */
class RB {

    public $withEFLB;
    public $withoutEFLB;
    public $FB;
    public $FLB;

    /**
     * @param BudgetTable $FB
     * @param BudgetTable $FLB
     */
    public function __construct(BudgetTable $FB, BudgetTable $FLB)
    {
        $this->FB = $FB;
        $this->FLB = $FLB;
        $this->withEFLB = 5;
        $this->withEFLB = $this->getRBWithEFLB();
        $this->withoutEFLB = $this->getRBWithoutEFLB();

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
        // @complex
        $this->FLB->totals->calculated_budget = $total;
        // $this->FLB->getTotal()->getCalculatedBudget()

        // @complex
        $this->FLB->totals->budget = '100.00';

        $this->calculateRemainingFLB();
    }

    public function calculateUnallocatedRow()
    {
        // This one is okay because you are using the value but not changing it ($this->FB->totals->budget)
        $unallocated_budget = 100 - $this->FLB->totals->budget;

        $unallocated_row = [
            'budget' => $unallocated_budget,
            'calculated_budget' => $this->withoutEFLB / 100 * $unallocated_budget,
            'remaining' => $this->withoutEFLB / 100 * $unallocated_budget
        ];

        // @complex
        $this->FLB->unallocated = $unallocated_row;
    }

    public function calculateRemainingFLB()
    {
        $remaining = 0;
        foreach ($this->FLB->tags as $tag) {
            $remaining+= $tag->remaining;
        }

        $remaining+= $this->FLB->unallocated['remaining'];

        // @complex
        $this->FLB->totals->remaining = $remaining;
    }

    /**
     * Get the user's remaining balance (RB), with EFLB in the formula.
     * Still figuring out the formula and if this is the figure we want.
     * @return int
     */
    private function getRBWithEFLB()
    {
//        $budgetTableTotalsService = new BudgetTableTotalsService($this);
//        $tagsRepository = new TagsRepository();
        //If totalsservice is calling this file, this file should not call TotalsService (unless tightly coupled, but rare)
        //(put the getCredit method in this file)
        //maybe interface if two repositories have similar methods?
        //flex budget repository and fixed budget repository and they would share same methods, interface budget
        //or extend budgetrepository
        $basicTotals = BasicTotal::createFromDatabase();

        return $this->calculateRemainingBalance($basicTotals, $this->FB, $this->FLB);
    }

    /**
     * Get remaining balance without the expenses with flex budget.
     * Still figuring out the formula and if this is the figure we want.
     * @return int
     */
    private function getRBWithoutEFLB()
    {
        return $this->getRBWithEFLB() - $this->FLB->totals->spentAfterSD;
    }

    /**
     * Calculate remaining balance
     * @param BasicTotal $basicTotals => Totals not related to budgets, but related to transactions
     * @param FixedBudgetTotal $fixedBudgetTotal => Totals related to fixed budgets
     * @param FlexBudgetTable $flexBudget => Totals related to flex budgets
     * @return mixed
     */
    public function calculateRemainingBalance(BasicTotal $basicTotals, FixedBudgetTotal $fixedBudgetTotal, FlexBudgetTable $flexBudget)
    {
        $RB =
            $basicTotals->credit // Total of income from the user regardless of budgets
            - $fixedBudgetTotal->totals->remaining // Total remainings on the fixed budget table
            + $basicTotals->EWB // Total of all the expenses without budget
            + $flexBudget->totals->spentBeforeSD // Total of spent before starting date for flex budgets
            + $flexBudget->totals->spentAfterSD // Total of spent after starting date for flex budgets
            + $fixedBudgetTotal->totals->spentBeforeSD // Total of spent before starting date for fixed budgets
            + $fixedBudgetTotal->totals->spentAfterSD // Total of spent after starting date for fixed budgets
            - $basicTotals->savings; // Savings

        return $RB;
    }
}