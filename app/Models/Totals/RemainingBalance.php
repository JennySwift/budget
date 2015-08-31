<?php

namespace App\Models\Totals;

/**
 * Class RemainingBalance
 * @package App\Totals
 * @TODO Take a look at the calculate... methods and think about the responsability of the RB object
 * Does this line of code belongs on the RB object? Are you setting a property on a property of a property of an object?
 * See @complex
 * And try to use proper naming (trust me, it is worth it!!!!! :))
 */
class RemainingBalance {

    public $withEFLB;
    public $withoutEFLB;
    public $FB;
    public $FLB;
    /**
     * @var
     */
    private $basicTotals;
    /**
     * @var
     */
    private $fixedBudgetTotal;
    /**
     * @var
     */
    private $flexBudgetTotal;

    /**
     * RemainingBalance constructor.
     */
    public function __construct(BasicTotal $basicTotals, FixedBudgetTotal $fixedBudgetTotal, FlexBudgetTotal $flexBudgetTotal)
    {
        $this->basicTotals = $basicTotals;
        $this->fixedBudgetTotal = $fixedBudgetTotal;
        $this->flexBudgetTotal = $flexBudgetTotal;
    }



    /**
     * @param BudgetTable $FB
     * @param BudgetTable $FLB
     */
//    public function __construct(BudgetTable $FB, BudgetTable $FLB)
//    {
//        $this->FB = $FB;
//        $this->FLB = $FLB;
//        $this->withEFLB = 5;
//        $this->withEFLB = $this->getRBWithEFLB();
//        $this->withoutEFLB = $this->getRBWithoutEFLB();
//
//        //Now that we have RB, get the calculated budgets for tags with FLB
//        $this->calculateBudgets();
//    }

    /**
     * Calculate remaining balance
     * @return mixed
     */
    public function calculate()
    {
        return $this->basicTotals->credit // Total of income from the user regardless of budgets
               - $this->fixedBudgetTotal->remaining // Total remainings on the fixed budget table
               + $this->basicTotals->EWB // Total of all the expenses without budget
               + $this->flexBudgetTotal->spentBeforeStartingDate // Total of spent before starting date for flex budgets
//               + $this->flexBudgetTotal->spentAfterStartingDate // Total of spent after starting date for flex budgets
               + $this->fixedBudgetTotal->spentBeforeStartingDate // Total of spent before starting date for fixed budgets
               + $this->fixedBudgetTotal->spentAfterStartingDate // Total of spent after starting date for fixed budgets
               - $this->basicTotals->savings; // Savings
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
}