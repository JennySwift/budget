<?php

namespace App\Models\Totals;

use App\Models\Budget;
use App\Contracts\Budgets\BudgetTotal;
use Illuminate\Contracts\Support\Arrayable;

class UnassignedBudgetTotal implements Arrayable, BudgetTotal {

    public $type;
    public $budgets;

    /**
     * Change to a static constructor or not, up to you
     * @param null $budgets
     */
    public function __construct($budgets = NULL)
    {
        $this->type = Budget::TYPE_UNASSIGNED;
        $this->budgets = $budgets ? : Budget::forCurrentUser()->whereType(Budget::TYPE_UNASSIGNED)->get();
    }

    /**
     * Calculate budgets totals
     * @param $column
     * @return mixed
     */
    public function calculate($column)
    {
        return $this->budgets->sum($column);
    }

    /**
     * Calculate budgets totals and set property
     * @param $column
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
            'spent' => $this->spent,
            'received' => $this->received,
        ];
    }
}