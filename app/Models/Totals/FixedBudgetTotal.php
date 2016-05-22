<?php

namespace App\Models\Totals;

use App\Http\Transformers\BudgetTransformer;
use App\Models\Budget;
use App\Contracts\Budgets\BudgetTotal;
use Illuminate\Contracts\Support\Arrayable;

class FixedBudgetTotal implements Arrayable, BudgetTotal {

    public $type;
    public $budgets;
    public $amount;
    public $remaining;
    public $cumulative;
    public $spentBeforeStartingDate;
    public $spentAfterStartingDate;
    public $receivedAfterStartingDate;

    /**
     * Change to a static constructor or not, up to you
     * @param null $budgets
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

        //Transform budgets
        $resource = createCollection($this->budgets, new BudgetTransformer);
        $this->budgets = transform($resource);
    }

    /**
     * Calculate budgets totals
     * @VP:
     * Why is $this->budgets->sum($column) working here even after I got rid of the
     * appended attributes? In other words, columns such as 'spentBeforeStartingDate'
     * are no longer appended, and yet the sum is still working here.
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
            'amount' => $this->amount,
            'remaining' => $this->remaining,
            'cumulative' => $this->cumulative,
            'spentBeforeStartingDate' => $this->spentBeforeStartingDate,
            'spentAfterStartingDate' => $this->spentAfterStartingDate,
            'receivedAfterStartingDate' => $this->receivedAfterStartingDate,
        ];
    }
}