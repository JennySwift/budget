<?php

namespace App\Models\Totals;

use App\Models\Budget;
use Illuminate\Contracts\Support\Arrayable;

class FlexBudgetTotal implements Arrayable {

    /**
     * Change to a static constructor or not, up to you
     */
    public function __construct()
    {
        $this->type = Budget::TYPE_FLEX;
        $this->budgets = Budget::whereType(Budget::TYPE_FLEX)->forCurrentUser()->get();
        $this->amount = $this->calculate('amount');
        // Todo: calculatedAmount
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
            'spentBeforeStartingDate' => $this->spentBeforeStartingDate,
            'spentAfterStartingDate' => $this->spentAfterStartingDate,
            'receivedAfterStartingDate' => $this->receivedAfterStartingDate,
//            'remaining' => $this->remaining,
        ];
    }

}