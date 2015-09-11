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
     * @var
     */
    public $amount = 0;

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

        $this->flexBudgetTotals->updateBudgets($this);
//        $this->updateBudgets();

        return $this;
    }

    /**
     * Now that we have RB, calculate the budget for each tag that has a FLB,
     * add the calculated_budget property to each tag,
     * and add each calculated_budget to show the total calculated budget in the total row
     *
     * @VP:
     * This method seems like it should be in the FlexBudgetTotal class,
     * but I had trouble accessing the remaining balance in the map method.
     * (See the commented-out method in FlexBudgetTotal.)
     *
     */
//    public function updateBudgets()
//    {
//        $this->flexBudgetTotals->budgets->map(function($budget){
//            $budget->calculatedAmount = $this->amount / 100 * $budget->amount;
//        });
//        $this->flexBudgetTotals->calculateAndSet('calculatedAmount');
//        $this->flexBudgetTotals->calculateAndSet('remaining');
//        $this->flexBudgetTotals->allocatedPlusUnallocatedCalculatedAmount = $this->amount;
//        $this->flexBudgetTotals->unallocatedCalculatedAmount = $this->amount - $this->flexBudgetTotals->calculatedAmount;
//        $this->flexBudgetTotals->unallocatedPlusCalculatedRemaining = $this->amount - $this->flexBudgetTotals->calculatedAmount;
//        $this->flexBudgetTotals->allocatedPlusUnallocatedRemaining = $this->amount + $this->flexBudgetTotals->spentAfterStartingDate + $this->flexBudgetTotals->receivedAfterStartingDate;
//        $this->flexBudgetTotals->unallocatedRemaining = $this->flexBudgetTotals->allocatedPlusUnallocatedRemaining - $this->flexBudgetTotals->remaining;
//    }

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
}