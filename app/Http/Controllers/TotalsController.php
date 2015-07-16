<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Transaction;
use App\Services\BudgetService;
use App\Services\GreetingService;
use Illuminate\Http\Request;

/**
 * Class TotalsController
 * @package App\Http\Controllers
 */
class TotalsController extends Controller
{

    /**
     *
     * @param GreetingService $greetingService
     * @return string
     */
    public function getGreeting(GreetingService $greetingService)
    {
        return $greetingService->greet();
    }

    /**
     *
     * @param Request $request
     * @return array
     */
    public function getAllocationTotals(Request $request)
    {
        return Transaction::getAllocationTotals($request->get('transaction_id'));
    }

    /**
     * @VP:
     * How do I access my BudgetService from routes.php so I don't need to create these methods?
     */


    /**
     *
     * @param BudgetService $budgetService
     * @return array
     */
    public function getBasicTotals(BudgetService $budgetService)
    {
        return $budgetService->getBasicTotals();
    }

    /**
     *
     * @param BudgetService $budgetService
     * @return array
     */
    public function getBudgetTotals(BudgetService $budgetService)
    {
        return $budgetService->getBudgetTotals();
    }

    /**
     *
     * @param BudgetService $budgetService
     * @return array
     */
    public function getBasicAndBudgetTotals(BudgetService $budgetService)
    {
        return $budgetService->getBasicAndBudgetTotals();
    }
}
