<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Savings\UpdateSavingsTotalRequest;
use App\Models\Savings;
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

        // @TODO Should be returning the savings object (with a transformer if you do not need everything :))
        return $savings->amount;
        // For consistency in your API, always return JSON
//        return response([
//            'amount' => $savings->amount
//        ], 200);
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

        return $savings->amount;
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

        return $savings->amount;
    }
}
