<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Transaction;
use App\Services\TotalsService;
use Illuminate\Http\Request;

/**
 * Class TotalsController
 * @package App\Http\Controllers
 */
class TotalsController extends Controller
{
    /**
     * @var TotalsService
     */
    protected $totalsService;

    /**
     * @param TotalsService $totalsService
     */
    public function __construct(TotalsService $totalsService)
    {
        $this->totalsService = $totalsService;
    }

    /**
     * Get allocation totals
     * POST /select/allocationTotals
     * @param Request $request
     * @return array
     */
    public function getAllocationTotals(Request $request)
    {
        $transaction = Transaction::find($request->get('transaction_id'));

        // It is good, but not ideal. Returning an AllocationTotal object that you could eventually use
        // with a transformer would be a good idea. But it is fine to keep like this if you want :)
        return $transaction->getAllocationTotals();
    }

    /**
     * Get basic and budget totals
     * GET /totals
     * @return array
     */
    public function index()
    {
        // Better way. Middleware? Compare session token with token sent with form?
        checkLoggedIn();

        return $this->totalsService->getBasicAndBudgetTotals();
    }

}
