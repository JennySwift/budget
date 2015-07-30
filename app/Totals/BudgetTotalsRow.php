<?php

namespace App\Totals;


/**
 * Class BudgetTotalsRow
 * @package App\Totals
 */
class BudgetTotalsRow {

    /**
     * @var
     */
    public $budget;

    /**
     * @var
     */
    public $spentBeforeSD;

    /**
     * @var
     */
    public $spentAfterSD;

    /**
     * @var
     */
    public $receivedAfterSD;

    /**
     * For if budget type is fixed
     * @var
     */
    public $cumulative;

    /**
     * Currently for flex budget this is calculated in RB.php
     * @var
     */
    public $remaining;

    /**
     * For flex budget. Currently calculated in RB.php
     * @var
     */
    public $calculated_budget;

    /**
     * @param $budget
     * @param $spentBeforeSD
     * @param $spentAfterSD
     * @param $receivedAfterSD
     */
    public function __construct($budget, $spentBeforeSD, $spentAfterSD, $receivedAfterSD)
    {
        $this->budget = $budget;
        $this->spentBeforeSD = $spentBeforeSD;
        $this->spentAfterSD = $spentAfterSD;
        $this->receivedAfterSD = $receivedAfterSD;
    }
}