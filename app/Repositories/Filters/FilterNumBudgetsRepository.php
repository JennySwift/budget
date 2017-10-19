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
        if (isset($value['in']) && $value['in'] && $value['in'] !== 'all') {
            $query = $this->filterInNumBudgets($query, $value);
        }
        if (isset($value['out']) && $value['out'] && $value['out'] !== 'none') {
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
        if (isset($value['in']) && $value['in'] === "zero") {
            $ids = Transaction::forCurrentUser()
                ->has('assignedBudgets', 0)
                ->pluck('id')->all();
        }
        elseif (isset($value['in']) && $value['in'] === "single") {
            $ids = Transaction::forCurrentUser()
                ->has('assignedBudgets', 1)
                ->pluck('id')->all();
        }
        elseif (isset($value['in']) && $value['in'] === "multiple") {
            $ids = Transaction::forCurrentUser()
                ->has('assignedBudgets', '>', 1)
                ->pluck('id')->all();
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
        if (isset($value['out']) && $value['out'] === "zero") {
            $ids = Transaction::forCurrentUser()
                ->has('assignedBudgets', 0)
                ->pluck('id')->all();
        }
        elseif (isset($value['out']) && $value['out'] === "single") {
            $ids = Transaction::forCurrentUser()
                ->has('assignedBudgets', 1)
                ->pluck('id')->all();
        }
        elseif (isset($value['out']) && $value['out'] === "multiple") {
            $ids = Transaction::forCurrentUser()
                ->has('assignedBudgets', '>', 1)
                ->pluck('id')->all();
        }

        return $query->whereNotIn('transactions.id', $ids);
    }
}