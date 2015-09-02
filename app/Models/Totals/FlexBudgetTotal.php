<?php

namespace App\Models\Totals;

use App\Contracts\Budgets\BudgetTotal;
use App\Models\Budget;
use Illuminate\Contracts\Support\Arrayable;

class FlexBudgetTotal implements Arrayable, BudgetTotal {

    public $type;
    public $budgets;
    public $amount;
    public $remaining;
    public $calculatedAmount;
    public $spentBeforeStartingDate;
    public $spentAfterStartingDate;
    public $receivedAfterStartingDate;

    /**
     * Change to a static constructor or not, up to you
     */
    public function __construct($budgets = NULL)
    {
        $this->type = Budget::TYPE_FLEX;
        $this->budgets = $budgets ? : Budget::forCurrentUser()->whereType(Budget::TYPE_FLEX)->get();
        $this->amount = $this->calculate('amount');
        $this->spentBeforeStartingDate = $this->calculate('spentBeforeStartingDate');
        $this->spentAfterStartingDate = $this->calculate('spentAfterStartingDate');
        $this->receivedAfterStartingDate = $this->calculate('receivedAfterStartingDate');
        // Todo: $this->remaining = $this->calculate('remaining');
        // Todo: unallocated totals
    }

    /**
     * Calculate budgets totals
     * @return mixed
     */
    public function calculate($column)
    {
        return $this->budgets->sum($column);
    }

    /**
     * Calculate budgets totals and set property
     * @return mixed
     */
    public function calculateAndSet($column)
    {
        $this->{$column} = $this->calculate($column);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => $this->type,
            'budget' => $this->budgets->toArray(),
            'amount' => $this->amount,
            'remaining' => $this->remaining,
            'calculatedAmount' => $this->calculatedAmount,
            'spentBeforeStartingDate' => $this->spentBeforeStartingDate,
            'spentAfterStartingDate' => $this->spentAfterStartingDate,
            'receivedAfterStartingDate' => $this->receivedAfterStartingDate,
//            'remaining' => $this->remaining,
        ];
    }

}