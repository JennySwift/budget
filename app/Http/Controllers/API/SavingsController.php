<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Savings\UpdateSavingsTotalRequest;
use App\Models\Savings;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Could use model binding for these routes
 * so I don't have to fetch the savings each time.
 * Route::put('increase/{savings}', 'SavingsController@increase');
 *
 * @TODO Should be returning the savings object,
 * (with a transformer if you do not need everything :))
 * not $savings->amount
 *
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

        return response($savings->amount, Response::HTTP_OK);
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

        return response($savings->amount, Response::HTTP_OK);
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

        return response($savings->amount, Response::HTTP_OK);
    }
}
