<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Savings\UpdateSavingsTotalRequest;
use App\Http\Requests\Savings\UpdateSavingsTotalWithFixedAmountRequest;
use App\Models\Savings;
use App\Services\TotalsService;
use Auth;
use DB;
use Illuminate\Http\Request;

/**
 * Class SavingsController
 * @package App\Http\Controllers
 */
class SavingsController extends Controller
{
    /**
     * Set the savings amount for the user
     * PUT api/savings/set
     * @param UpdateSavingsTotalRequest $updateSavingsTotalRequest
     * @return string
     */
    public function set(UpdateSavingsTotalRequest $updateSavingsTotalRequest)
    {
        $amount = $updateSavingsTotalRequest->get('amount');

        $savings = Savings::forCurrentUser()->first();
        $savings->update(compact('amount'));

        return number_format($savings->amount, 2);
    }

    /**
     * Increase the savings amount for the user
     * PUT api/savings/increase
     * @param UpdateSavingsTotalRequest $updateSavingsTotalRequest
     * @return mixed
     */
    public function increase(UpdateSavingsTotalRequest $updateSavingsTotalRequest)
    {
        $amount = $updateSavingsTotalRequest->get('amount');

        $savings = Savings::forCurrentUser()->first();
        $savings->increase($amount);
        $savings->save();

        return number_format($savings->amount, 2);
    }

    /**
     * Decrease the savings amount for the user
     * PUT api/savings/decrease
     * @param UpdateSavingsTotalRequest $updateSavingsTotalRequest
     * @return string
     */
    public function decrease(UpdateSavingsTotalRequest $updateSavingsTotalRequest)
    {
        $amount = $updateSavingsTotalRequest->get('amount');

        $savings = Savings::forCurrentUser()->first();
        $savings->decrease($amount);
        $savings->save();

        return number_format($savings->amount, 2);
    }
}
