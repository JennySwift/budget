<?php namespace App\Http\Controllers;

use App\Http\Requests;
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
     *
     * @param Request $request
     * @return mixed
     */
    public function updateSavingsTotal(Request $request)
    {
        $amount = $request->get('amount');
        Savings::updateSavingsTotal($amount);

        return Savings::getSavingsTotal();
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
        $percentage_of_RB = $request->get('percentage_of_RB');
        Savings::addPercentageToSavings($percentage_of_RB);

        return Savings::getSavingsTotal();
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function addPercentageToSavingsAutomatically(Request $request)
    {
        $amount_to_add = $request->get('amount_to_add');
        Savings::addPercentageToSavingsAutomatically($amount_to_add);

        return Savings::getSavingsTotal();
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function reverseAutomaticInsertIntoSavings(Request $request)
    {
        $amount_to_subtract = $request->get('amount_to_subtract');
        Savings::reverseAutomaticInsertIntoSavings($amount_to_subtract);

        return Savings::getSavingsTotal();
    }
}
