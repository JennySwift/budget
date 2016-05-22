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
     * @param UnassignedBudgetTotal $unassignedBudgetTotals
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