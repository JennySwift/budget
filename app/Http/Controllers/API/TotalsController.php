<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Transformers\SidebarTotalTransformer;
use Illuminate\Http\Response;

class TotalsController extends Controller
{
    /**
     * Get the totals
     * @return array
     */
    public function all()
    {
        $remainingBalance = app('remaining-balance')->calculate();

        return [
            'fixedBudgetTotals' => $remainingBalance->fixedBudgetTotals->toArray(),
            'flexBudgetTotals' => $remainingBalance->flexBudgetTotals->toArray(),
            'basicTotals' => $remainingBalance->basicTotals->toArray(),
            'remainingBalance' => $remainingBalance->amount
        ];
    }

    /**
     * Get the totals for the sidebar
     * GET api/totals/sidebar
     * @return mixed
     */
    public function sidebar()
    {
        $remainingBalance = app('remaining-balance')->calculate();

        $resource = $this->createItem($remainingBalance, new SidebarTotalTransformer);

        return $this->responseWithTransformer($resource, Response::HTTP_OK);
    }

    /**
     * Get the fixed budget totals
     * GET api/totals/fixedBudget
     * @return array
     */
    public function fixedBudget()
    {
        $remainingBalance = app('remaining-balance')->calculate();

        return $remainingBalance->fixedBudgetTotals->toArray();
    }

    /**
     * Get the flex budget totals
     * GET api/totals/flexBudget
     * @return array
     */
    public function flexBudget()
    {
        $remainingBalance = app('remaining-balance')->calculate();

        return $remainingBalance->flexBudgetTotals->toArray();
    }

    /**
     * Get the unassigned budget totals
     * @return array
     */
    public function unassignedBudget()
    {
        $remainingBalance = app('remaining-balance')->calculate();

        return $remainingBalance->unassignedBudgetTotals->toArray();
    }


}
