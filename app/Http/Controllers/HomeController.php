<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Color;
use App\Repositories\Tags\TagsRepository;
use App\Repositories\Transactions\FilterRepository;
use App\Services\TotalsService;
use Illuminate\Support\Facades\Auth;
use JavaScript;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     * GET /
     * @return Response
     */
    public function index(FilterRepository $filterRepository, TagsRepository $tagsRepository, TotalsService $totalsService)
    {
        JavaScript::put([
            //It wouldn't work if I named it 'transactions', or 'totals'
            'filter_response' => $filterRepository->filterTransactions(),
            'totals_response' => $totalsService->getBasicAndBudgetTotals(),
            'accounts_response' => Account::getAccounts(),
            'tags_response' => $tagsRepository->getTags(),
            'colors_response' => Color::getColors(),
            'me' => Auth::user(),
            'env' => app()->env
        ]);

        return view('home');
    }

}
