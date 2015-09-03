<?php

namespace App\Models\Totals;

use App\Contracts\Budgets\BudgetTotal;
use App\Models\Budget;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class FlexBudgetTotal
 * @package App\Models\Totals
 */
class FlexBudgetTotal implements Arrayable, BudgetTotal {

    /**
     * @var string
     */
    public $type;

    /**
     * @var
     */
    public $budgets;

    /**
     * @var mixed
     */
    public $amount;

    /**
     * @var
     */
    public $remaining;

    /**
     * @var
     */
    public $calculatedAmount;

    /**
     * @var mixed
     */
    public $spentBeforeStartingDate;

    /**
     * @var mixed
     */
    public $spentAfterStartingDate;

    /**
     * @var mixed
     */
    public $receivedAfterStartingDate;

    /**
     * @var int
     */
    public $unallocatedAmount;

    /**
     * @var int
     */
    public $allocatedPlusUnallocatedAmount;
    /**
     * @var
     */
    public $allocatedPlusUnallocatedCalculatedAmount;

    /**
     * @var
     */
    public $unallocatedCalculatedAmount;

    /**
     * @var
     */
    public $allocatedPlusUnallocatedRemaining;

    /**
     * @var
     */
    public $unallocatedRemaining;

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
        $this->unallocatedAmount = 100 - $this->amount;
        $this->allocatedPlusUnallocatedAmount = 100;
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
            'allocatedAmount' => $this->amount,
            'allocatedRemaining' => $this->remaining,
            'allocatedCalculatedAmount' => $this->calculatedAmount,
            'spentBeforeStartingDate' => $this->spentBeforeStartingDate,
            'spentAfterStartingDate' => $this->spentAfterStartingDate,
            'receivedAfterStartingDate' => $this->receivedAfterStartingDate,
            'unallocatedAmount' => $this->unallocatedAmount,
            'allocatedPlusUnallocatedAmount' => $this->allocatedPlusUnallocatedAmount,
            'allocatedPlusUnallocatedCalculatedAmount' => $this->allocatedPlusUnallocatedCalculatedAmount,
            'unallocatedCalculatedAmount' => $this->unallocatedCalculatedAmount,
            'allocatedPlusUnallocatedRemaining' => $this->allocatedPlusUnallocatedRemaining,
            'unallocatedRemaining' => $this->unallocatedRemaining
        ];
    }

}