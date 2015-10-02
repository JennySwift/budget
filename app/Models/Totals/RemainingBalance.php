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
     * @var UnassignedBudgetTotal
     */
    public $unassignedBudgetTotals;

    /**
     * @var
     */
    public $amount = 0;

    /**
     * RemainingBalance constructor.
     * @param BasicTotal $basicTotals
     * @param FixedBudgetTotal $fixedBudgetTotals
     * @param FlexBudgetTotal $flexBudgetTotals
     * @param UnassignedBudgetTotal $unassignedBudgetTotal
     */
    public function __construct(BasicTotal $basicTotals, FixedBudgetTotal $fixedBudgetTotals, FlexBudgetTotal $flexBudgetTotals, UnassignedBudgetTotal $unassignedBudgetTotals)
    {
        $this->basicTotals = $basicTotals;
        $this->fixedBudgetTotals = $fixedBudgetTotals;
        $this->flexBudgetTotals = $flexBudgetTotals;
        $this->unassignedBudgetTotals = $unassignedBudgetTotals;
    }

    /**
     * Calculate remaining balance
     * @return mixed
     */
    public function calculate()
    {
        $this->amount = $this->basicTotals->credit
            - $this->fixedBudgetTotals->remaining
            + $this->basicTotals->expensesWithoutBudget
            + $this->flexBudgetTotals->spentBeforeStartingDate
            + $this->fixedBudgetTotals->spentBeforeStartingDate
            + $this->fixedBudgetTotals->spentAfterStartingDate
            - $this->basicTotals->savings;

        $this->flexBudgetTotals->updateBudgets($this);

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