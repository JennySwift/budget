<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
}
