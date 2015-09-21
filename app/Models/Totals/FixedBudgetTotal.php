<?php

namespace App\Models\Totals;

use App\Models\Budget;
use App\Contracts\Budgets\BudgetTotal;
use Illuminate\Contracts\Support\Arrayable;

class FixedBudgetTotal implements Arrayable, BudgetTotal {

    public $type;
    public $budgets;

    /**
     * Change to a static constructor or not, up to you
     */
    public function __construct($budgets = NULL)
    {
        $this->type = Budget::TYPE_FIXED;
        $this->budgets = $budgets ? : Budget::forCurrentUser()->whereType(Budget::TYPE_FIXED)->get();
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
            'amount' => $this->amount,
            'remaining' => $this->remaining,
            'cumulative' => $this->cumulative,
            'spentBeforeStartingDate' => $this->spentBeforeStartingDate,
            'spentAfterStartingDate' => $this->spentAfterStartingDate,
            'receivedAfterStartingDate' => $this->receivedAfterStartingDate,
        ];
    }
}