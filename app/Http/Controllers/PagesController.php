<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Account;
use App\Models\Color;
use App\Repositories\Tags\TagsRepository;
use App\Repositories\Transactions\FilterRepository;
use App\Services\TotalsService;
use Auth, JavaScript;

/**
 * Class PagesController
 * @package App\Http\Controllers
 */
class PagesController extends Controller {

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
     * @param FilterRepository $filterRepository
     * @param TagsRepository $tagsRepository
     * @param TotalsService $totalsService
     * @return Response
     */
    public function home(FilterRepository $filterRepository, TagsRepository $tagsRepository, TotalsService $totalsService)
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

    /**
     * Show the application dashboard to the user.
     * GET /accounts
     * @return Response
     */
    public function accounts()
    {
        JavaScript::put([
            'me' => Auth::user(),
            'accounts' => Account::getAccounts()
        ]);

        return view('accounts');
    }

    /**
     * Show the application dashboard to the user.
     * GET /budgets
     * @param TagsRepository $tagsRepository
     * @param TotalsService $totalsService
     * @return Response
     */
    public function budgets(TagsRepository $tagsRepository, TotalsService $totalsService)
    {
        JavaScript::put([
            'me' => Auth::user(),
            'tags_response' => $tagsRepository->getTags(),
            'totals_response' => $totalsService->getBasicAndBudgetTotals()
        ]);

        return view('budgets');
    }

    /**
     * Display a listing of the resource.
     * GET /charts
     * @return Response
     */
    public function charts()
    {
        JavaScript::put([
            'me' => Auth::user()
        ]);

        return view('charts');
    }

    /**
     * Display a listing of the resource.
     * GET /help
     * @return Response
     */
    public function help()
    {
        JavaScript::put([
            'me' => Auth::user(),
        ]);

        return view('help');
    }

    /**
     * Show the application dashboard to the user.
     * GET /tags
     * @return Response
     */
    public function tags()
    {
        JavaScript::put([
            'me' => Auth::user()
        ]);

        return view('tags');
    }

}
