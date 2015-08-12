<?php

namespace App\Totals;


class FixedBudgetTable extends BudgetTable {

    public function __construct()
    {
        $this->type = 'fixed';
        $this->tags = $this->getTagsWithFixedBudget();
        $this->totals = $this->getTotalsForSpecifiedBudget();
        $this->totals->remaining = $this->getRemainingBudget();
        $this->totals->cumulative = $this->getCumulativeBudget();
        $this->fixedBudget = $this->getBudget();
    }

}