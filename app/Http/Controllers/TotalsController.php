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
        return Transaction::getAllocationTotals($request->get('transaction_id'));
    }

    /**
     * Get basic and budget totals
     * @return array
     */
    public function index()
    {
        //I don't need to pass the Total parameter here
        //because I have put it in my constructor.
        //If I instead did new TotalsService(),
        //then I would need to pass the Total parameter.
        return $this->totalsService->getBasicAndBudgetTotals();
    }
}
