<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Budget;
use App\Models\Tag;
use App\Models\Transaction;
use App\Services\BudgetService;
use Auth;
use DB;
use Illuminate\Http\Request;
use JavaScript;

/**
 * Class BudgetsController
 * @package App\Http\Controllers
 */
class BudgetsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index(BudgetService $budgetService)
    {
        JavaScript::put([
            'me' => Auth::user(),
            'totals_response' => $budgetService->getBasicAndBudgetTotals(),
        ]);

        return view('budgets');
    }
}
