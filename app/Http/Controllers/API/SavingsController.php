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
     * Set the amount..
     * @param UpdateSavingsTotalRequest $updateSavingsTotalRequest
     * @return string
     */
    public function set(UpdateSavingsTotalRequest $updateSavingsTotalRequest)
    {
        $amount = $updateSavingsTotalRequest->get('amount');

        $account = Savings::forCurrentUser()->first();
        $account->update(compact('amount'));

        return number_format($account->amount, 2);
    }

    /**
     * Increase it..
     * @param UpdateSavingsTotalRequest $updateSavingsTotalRequest
     * @return mixed
     */
    public function increase(UpdateSavingsTotalRequest $updateSavingsTotalRequest)
    {
        $amount = $updateSavingsTotalRequest->get('amount');

        $account = Savings::forCurrentUser()->first();
        $account->increase($amount);
        $account->save();

//        Savings::forCurrentUser()->update(compact('amount'));

        return number_format($account->amount, 2);
    }

    /**
     * Decrease it!
     * @param UpdateSavingsTotalRequest $updateSavingsTotalRequest
     * @return string
     */
    public function decrease(UpdateSavingsTotalRequest $updateSavingsTotalRequest)
    {
        $amount = $updateSavingsTotalRequest->get('amount');

        $account = Savings::forCurrentUser()->first();
        $account->decrease($amount);
        $account->save();

        return number_format($account->amount, 2);
    }


    /**
     * Whereas updateSavingsTotal just changes the total,
     * this function adds or subtracts from the current total.
     * @param Request $request
     * @return mixed
     */
//    public function updateSavingsTotalWithPercentage(Request $request)
//    {
//        Savings::addPercentageToSavings($request->get('percentage_of_RB'));
//
//        return Savings::getSavingsTotal();
//    }



}
