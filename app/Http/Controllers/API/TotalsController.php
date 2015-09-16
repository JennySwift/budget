<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Transformers\SidebarTotalTransformer;

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
     * @return mixed
     */
    public function sidebar()
    {
        $remainingBalance = app('remaining-balance')->calculate();

        $resource = $this->createItem($remainingBalance, new SidebarTotalTransformer);

        return $this->responseWithTransformer($resource, 200);
    }
}
