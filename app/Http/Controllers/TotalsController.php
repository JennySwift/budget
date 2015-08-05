<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Transaction;
use App\Totals\TotalsService;
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
        checkLoggedIn();
        return Transaction::getAllocationTotals($request->get('transaction_id'));
    }

    /**
     * Get basic and budget totals
     * @return array
     */
    public function index()
    {
        checkLoggedIn();
        return $this->totalsService->getBasicAndBudgetTotals();
    }
}
