<?php

namespace App\Models\Totals;

use App\Models\Budget;
use Illuminate\Contracts\Support\Arrayable;

class FixedBudgetTotal implements Arrayable {

    /**
     * Change to a static constructor or not, up to you
     */
    public function __construct($budgets)
    {
        $this->type = Budget::TYPE_FIXED;
        $this->budgets = $budgets;
        $this->amount = $this->calculate('amount');
        $this->remaining = $this->calculate('remaining');
        $this->cumulative = $this->calculate('cumulative');
        $this->spentBeforeStartingDate = $this->calculate('spentBeforeStartingDate');
        $this->spentAfterStartingDate = $this->calculate('spentAfterStartingDate');
        $this->receivedAfterStartingDate = $this->calculate('receivedAfterStartingDate');
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
            'remaining' => $this->remaining,
            'cumulative' => $this->cumulative,
            'spentBeforeStartingDate' => $this->spentBeforeStartingDate,
            'spentAfterStartingDate' => $this->spentAfterStartingDate,
            'receivedAfterStartingDate' => $this->receivedAfterStartingDate,
        ];
    }

}