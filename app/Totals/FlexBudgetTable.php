<?php

namespace App\Totals;


class FlexBudgetTable extends BudgetTable {

    /**
     * Change to a static constructor or not, up to you
     */
    public function __construct()
    {
        $this->type = BudgetTable::TYPE_FLEX;
        $this->tags = $this->getTagsWithFlexBudget();
        $this->totals = $this->getTotalsForSpecifiedBudget();
    }

}