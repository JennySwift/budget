<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Savings\UpdateSavingsTotalRequest;
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
     *
     * @param UpdateSavingsTotalRequest $updateSavingsTotalRequest
     * @return mixed
     */
    public function updateSavingsTotal(UpdateSavingsTotalRequest $updateSavingsTotalRequest)
    {
        $amount = $updateSavingsTotalRequest->get('amount');

        Savings::forCurrentUser()->update(compact('amount', 'other'));

        return number_format($amount, 2);
    }

    /**
     * Whereas updateSavingsTotal just changes the total,
     * this function adds or subtracts from the current total.
     * @param Request $request
     * @return mixed
     */
    public function addFixedToSavings(Request $request)
    {
        $amount_to_add = $request->get('amount_to_add');
        Savings::addFixedToSavings($amount_to_add);

        return Savings::getSavingsTotal();
    }

    /**
     * Whereas updateSavingsTotal just changes the total,
     * this function adds or subtracts from the current total.
     * @param Request $request
     * @return mixed
     */
    public function addPercentageToSavings(Request $request)
    {
        Savings::addPercentageToSavings($request->get('percentage_of_RB'));

        return Savings::getSavingsTotal();
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function addPercentageToSavingsAutomatically(Request $request)
    {
        $totalsService = new TotalsService();
        Savings::addPercentageToSavingsAutomatically($request->get('amount_to_add'));

        return $totalsService->getBasicAndBudgetTotals();
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
//    public function reverseAutomaticInsertIntoSavings(Request $request)
//    {
//        Savings::reverseAutomaticInsertIntoSavings($request->get('amount_to_subtract'));
//
//        return $this->budgetService->getBasicAndBudgetTotals();
//    }
}
