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

//    public $withEFLB;
//    public $withoutEFLB;
//    public $FB;
//    public $FLB;
    /**
     * @var
     */
    public $basicTotals;

    /**
     * @var
     */
    public $fixedBudgetTotals;

    /**
     * @var
     */
    public $flexBudgetTotals;

    /**
     * RemainingBalance constructor.
     */
    public function __construct(BasicTotal $basicTotals, FixedBudgetTotal $fixedBudgetTotals, FlexBudgetTotal $flexBudgetTotals)
    {
        $this->basicTotals = $basicTotals;
        $this->fixedBudgetTotals = $fixedBudgetTotals;
        $this->flexBudgetTotals = $flexBudgetTotals;
    }

    /**
     * Calculate remaining balance
     * @return mixed
     */
    public function calculate()
    {
        $this->amount = $this->basicTotals->credit // Total of income from the user regardless of budgets
            - $this->fixedBudgetTotals->remaining // Total remainings on the fixed budget table
            + $this->basicTotals->EWB // Total of all the expenses without budget
            + $this->flexBudgetTotals->spentBeforeStartingDate // Total of spent before starting date for flex budgets
//               + $this->flexBudgetTotal->spentAfterStartingDate // Total of spent after starting date for flex budgets
            + $this->fixedBudgetTotals->spentBeforeStartingDate // Total of spent before starting date for fixed budgets
            + $this->fixedBudgetTotals->spentAfterStartingDate // Total of spent after starting date for fixed budgets
            - $this->basicTotals->savings; // Savings

        $this->updateBudgets();

        return $this;
    }

    /**
     * Now that we have RB, calculate the budget for each tag that has a FLB,
     * add the calculated_budget property to each tag,
     * and add each calculated_budget to show the total calculated budget in the total row
     */
    public function updateBudgets()
    {
        $this->flexBudgetTotals->budgets->map(function($budget){
            $budget->calculatedAmount = $this->amount / 100 * $budget->amount;
        });
        $this->flexBudgetTotals->calculateAndSet('calculatedAmount');
        $this->flexBudgetTotals->calculateAndSet('remaining');
        $this->flexBudgetTotals->allocatedPlusUnallocatedCalculatedAmount = $this->amount;
        $this->flexBudgetTotals->unallocatedCalculatedAmount = $this->amount - $this->flexBudgetTotals->calculatedAmount;
        $this->flexBudgetTotals->unallocatedPlusCalculatedRemaining = $this->amount - $this->flexBudgetTotals->calculatedAmount;
        $this->flexBudgetTotals->allocatedPlusUnallocatedRemaining = $this->amount + $this->flexBudgetTotals->spentAfterStartingDate + $this->flexBudgetTotals->receivedAfterStartingDate;
        $this->flexBudgetTotals->unallocatedRemaining = $this->flexBudgetTotals->allocatedPlusUnallocatedRemaining - $this->flexBudgetTotals->remaining;
//        $total = 0;
//        foreach ($this->flexBudgetTotals as $tag) {
//            $tag->calculated_budget = $this->withoutEFLB / 100 * $tag->flex_budget;
//            $total+= $tag->calculated_budget;
//        }
//        $this->calculateUnallocatedRow();
//
//        $total+= $this->FLB->unallocated['calculated_budget'];
//        // @complex
//        $this->FLB->totals->calculated_budget = $total;
//        // $this->FLB->getTotal()->getCalculatedBudget()
//
//        // @complex
//        $this->FLB->totals->budget = '100.00';
//
//        $this->calculateRemainingFLB();
    }

    /**
     * @param BasicTotal $basicTotals
     * @return $this
     */
    public function setBasicTotals(BasicTotal $basicTotals)
    {
        $this->basicTotals = $basicTotals;

        return $this;
    }

    /**
     * @param FixedBudgetTotal $fixedBudgetTotals
     * @return $this
     */
    public function setFixedBudgetTotals(FixedBudgetTotal $fixedBudgetTotals)
    {
        $this->fixedBudgetTotals = $fixedBudgetTotals;

        return $this;
    }

    /**
     * @param FlexBudgetTotal $flexBudgetTotals
     * @return $this
     */
    public function setFlexBudgetTotals(FlexBudgetTotal $flexBudgetTotals)
    {
        $this->flexBudgetTotals = $flexBudgetTotals;

        return $this;
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


//
//    public function calculateUnallocatedRow()
//    {
//        // This one is okay because you are using the value but not changing it ($this->FB->totals->budget)
//        $unallocated_budget = 100 - $this->FLB->totals->budget;
//
//        $unallocated_row = [
//            'budget' => $unallocated_budget,
//            'calculated_budget' => $this->withoutEFLB / 100 * $unallocated_budget,
//            'remaining' => $this->withoutEFLB / 100 * $unallocated_budget
//        ];
//
//        // @complex
//        $this->FLB->unallocated = $unallocated_row;
//    }
//
//    public function calculateRemainingFLB()
//    {
//        $remaining = 0;
//        foreach ($this->FLB->tags as $tag) {
//            $remaining+= $tag->remaining;
//        }
//
//        $remaining+= $this->FLB->unallocated['remaining'];
//
//        // @complex
//        $this->FLB->totals->remaining = $remaining;
//    }
}