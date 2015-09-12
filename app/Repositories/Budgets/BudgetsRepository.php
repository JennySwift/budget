<?php

namespace App\Repositories\Budgets;

use App\Models\Budget;

/**
 * Class BudgetsRepository
 * @package App\Repositories\Budgets
 */
class BudgetsRepository
{
    /**
     *
     * @return mixed
     */
    public function getBudgets()
    {
        return Budget::forCurrentUser()
            ->orderBy('name', 'asc')
            ->get();
    }

}