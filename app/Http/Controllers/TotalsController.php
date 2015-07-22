<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Transaction;
use App\Services\BudgetService;
use App\Services\GreetingService;
use App\Services\TotalsService;
use Illuminate\Http\Request;

/**
 * Class TotalsController
 * @package App\Http\Controllers
 */
class TotalsController extends Controller
{
    protected $totalsService;

    public function __construct(TotalsService $totalsService)
    {
        $this->totalsService = $totalsService;
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
    public function getBasicTotals()
    {
        return $this->totalsService->getBasicTotals();
    }

    /**
     *
     * @param BudgetService $budgetService
     * @return array
     */
    public function getBudgetTotals()
    {
        return $this->totalsService->getBudgetTotals();
    }

    /**
     *
     * @param BudgetService $budgetService
     * @return array
     */
    public function getBasicAndBudgetTotals()
    {
        return $this->totalsService->getBasicAndBudgetTotals();
    }
}
