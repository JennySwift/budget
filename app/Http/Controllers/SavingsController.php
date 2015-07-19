<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Savings;
use App\Services\BudgetService;
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
     * @var
     */
    protected $budgetService;

    /**
     * @param BudgetService $budgetService
     */
    public function __construct(BudgetService $budgetService)
    {
        $this->budgetService = $budgetService;
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function updateSavingsTotal(Request $request)
    {
        Savings::where('user_id', Auth::user()->id)
            ->update([
                'amount' => $request->get('amount')
            ]);

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
        Savings::addPercentageToSavingsAutomatically($request->get('amount_to_add'));

        return $this->budgetService->getBasicAndBudgetTotals();
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
