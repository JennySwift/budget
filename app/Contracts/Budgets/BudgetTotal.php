<?php

namespace App\Contracts\Budgets;

/**
 * Interface BudgetTotal
 * @package App\Contracts\Budgets
 */
interface BudgetTotal
{
    /**
     * Calculate budgets totals
     * @return mixed
     */
    public function calculate($column);

    /**
     * Calculate budgets totals and set property
     * @return mixed
     */
    public function calculateAndSet($column);
}