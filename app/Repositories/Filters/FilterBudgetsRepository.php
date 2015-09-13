<?php namespace App\Repositories\Filters;

/**
 * Class FilterBudgetsRepository
 */
class FilterBudgetsRepository {

    /**
     * Filter the transactions for those that have all the budgets (budgets) searched for
     * @param $query
     * @param $budgets
     * @return mixed
     */
    public function filterBudgets($query, $budgets)
    {
        $query = $this->filterInBudgets($query, $budgets);
        $query = $this->filterOutBudgets($query, $budgets);

        return $query;
    }

    /**
     *
     * @param $query
     * @param $budgets
     * @return mixed
     */
    private function filterInBudgets($query, $budgets)
    {
        if ($budgets['in']['and']) {
            //Make an array of the budget ids searched for
            $budget_ids = array();
            foreach ($budgets['in']['and'] as $budget) {
                $budget_ids[] = $budget['id'];
            }

            //Add to the $query
            foreach ($budget_ids as $budget_id) {
                $query = $query->whereHas('budgets', function ($q) use ($budget_id) {
                    $q->where('budgets.id', $budget_id);
                });
            }
        }

        if ($budgets['in']['or']) {
            //Make an array of the budget ids searched for
            $budget_ids = array();
            foreach ($budgets['in']['or'] as $budget) {
                $budget_ids[] = $budget['id'];
            }

            //Add to the $query
            $query = $query->whereHas('budgets', function ($q) use ($budget_ids) {
                $q->whereIn('budgets.id', $budget_ids);
            });
        }

        return $query;
    }

    /**
     *
     * @param $query
     * @param $budgets
     * @return mixed
     */
    private function filterOutBudgets($query, $budgets)
    {
        if ($budgets['out']) {
            //Make an array of the budget ids searched for
            $budget_ids = array();
            foreach ($budgets['out'] as $budget) {
                $budget_ids[] = $budget['id'];
            }

            //Add to the $query
            foreach ($budget_ids as $budget_id) {
                $query = $query->whereDoesntHave('budgets', function ($q) use ($budget_id) {
                    $q->where('budgets.id', $budget_id);
                });
            }
        }

        return $query;
    }
}