<?php

namespace App\Models\Totals;

// new FixedBudgetTable
// FixedBudgetTable::createFromDatabase
// FixedBudgetTable::createFromJson($json)
// FixedBudgetTable::createFromArray($array)

class FixedBudgetTable extends BudgetTable {

    /**
     * Change to a static constructor or not, up to you
     */
    public function __construct()
    {
        $this->type = BudgetTable::TYPE_FIXED;
        $this->tags = $this->getTagsWithFixedBudget();
        $this->totals = $this->getTotalsForSpecifiedBudget();
        $this->totals->remaining = $this->getRemainingBudget();
        $this->totals->cumulative = $this->getCumulativeBudget();
        $this->fixedBudget = $this->getBudget();
    }

}