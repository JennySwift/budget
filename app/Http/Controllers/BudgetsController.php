<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Budget;
use App\Models\Tag;
use App\Models\Transaction;
use App\Repositories\Tags\TagsRepository;
use App\Services\BudgetService;
use App\Services\TotalsService;
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
    public function index(BudgetService $budgetService, TagsRepository $tagsRepository, TotalsService $totalsService)
    {
        JavaScript::put([
            'me' => Auth::user(),
            'tags_response' => $tagsRepository->getTags(),
            'totals_response' => $totalsService->getBasicAndBudgetTotals($budgetService),
        ]);

        return view('budgets');
    }
}
