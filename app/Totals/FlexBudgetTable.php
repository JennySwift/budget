<?php

namespace App\Totals;


class FlexBudgetTable extends BudgetTable {

    public function __construct()
    {
        $this->type = 'flex';
        $this->tags = $this->getTagsWithFlexBudget();
        $this->totals = $this->getTotalsForSpecifiedBudget();
    }
}