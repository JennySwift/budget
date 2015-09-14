<?php

namespace App\Repositories\Filters;


use App\Models\Transaction;
use Auth;

/**
 * Class FilterNumBudgetsRepository
 * @package App\Repositories\Filters
 */
class FilterNumBudgetsRepository {

    /**
     * Filter by the number of budgets associated with a transaction
     * @param $query
     * @param $value
     * @return mixed
     */
    public function filterNumBudgets($query, $value)
    {
        if ($value['in'] && $value['in'] !== 'all') {
            $query = $this->filterInNumBudgets($query, $value);
        }
        if ($value['out'] && $value['out'] !== 'none') {
            $query = $this->filterOutNumBudgets($query, $value);
        }

        return $query;
    }

    /**
     *
     * @param $query
     * @param $value
     * @return mixed
     */
    private function filterInNumBudgets($query, $value)
    {
        if ($value['in'] === "zero") {
            $ids = Transaction::forCurrentUser()
                ->has('assignedBudgets', 0)
                ->lists('id');
        }
        elseif ($value['in'] === "single") {
            $ids = Transaction::forCurrentUser()
                ->has('assignedBudgets', 1)
                ->lists('id');
        }
        elseif ($value['in'] === "multiple") {
            $ids = Transaction::forCurrentUser()
                ->has('assignedBudgets', '>', 1)
                ->lists('id');
        }

        return $query->whereIn('transactions.id', $ids);
    }

    /**
     *
     * @param $query
     * @param $value
     * @return mixed
     */
    private function filterOutNumBudgets($query, $value)
    {
        if ($value['out'] === "zero") {
            $ids = Transaction::forCurrentUser()
                ->has('assignedBudgets', 0)
                ->lists('id');
        }
        elseif ($value['out'] === "single") {
            $ids = Transaction::forCurrentUser()
                ->has('assignedBudgets', 1)
                ->lists('id');
        }
        elseif ($value['out'] === "multiple") {
            $ids = Transaction::forCurrentUser()
                ->has('assignedBudgets', '>', 1)
                ->lists('id');
        }

        return $query->whereNotIn('transactions.id', $ids);
    }
}