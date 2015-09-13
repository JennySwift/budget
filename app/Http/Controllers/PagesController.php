<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Account;
use App\Repositories\Budgets\BudgetsRepository;
use App\Repositories\Filters\FilterRepository;
use Auth, JavaScript;

/**
 * Class PagesController
 * @package App\Http\Controllers
 */
class PagesController extends Controller {

    /**
     * @var BudgetsRepository
     */
    private $budgetsRepository;

    /**
     * Create a new controller instance.
     */
    public function __construct(BudgetsRepository $budgetsRepository)
    {
        $this->middleware('auth');
        $this->budgetsRepository = $budgetsRepository;
    }

    /**
     * Show the application dashboard to the user.
     * GET /
     * @param FilterRepository $filterRepository
     * @return Response
     */
    public function home(FilterRepository $filterRepository)
    {
        $remainingBalance = app('remaining-balance')->calculate();

        JavaScript::put([
            'env' => app()->env,
            'me' => Auth::user(),
            //It wouldn't work if I named it 'transactions', or 'totals'
            'accounts_response' => Account::getAccounts(),
            'budgets' => $this->budgetsRepository->getBudgets(),
            'filter_response' => $filterRepository->filterTransactions(),

            'fixedBudgetTotals' => $remainingBalance->fixedBudgetTotals->toArray(),
            'flexBudgetTotals' => $remainingBalance->flexBudgetTotals->toArray(),
            'basicTotals' => $remainingBalance->basicTotals->toArray(),
            'remainingBalance' => $remainingBalance->amount
        ]);

        return view('home');
    }

    /**
     * Show the application dashboard to the user.
     * GET /budgets
     * @return Response
     */
    public function budgets()
    {
        $remainingBalance = app('remaining-balance')->calculate();

        JavaScript::put([
            'me' => Auth::user(),
            'fixedBudgets' => $remainingBalance->fixedBudgetTotals->budgets,
            'flexBudgets' => $remainingBalance->flexBudgetTotals->budgets,
            'fixedBudgetTotals' => $remainingBalance->fixedBudgetTotals->toArray(),
            'flexBudgetTotals' => $remainingBalance->flexBudgetTotals->toArray(),
            'basicTotals' => $remainingBalance->basicTotals->toArray(),
            'remainingBalance' => $remainingBalance->amount
        ]);

        return view('budgets');
    }

    public function preferences()
    {
        JavaScript::put([
            'me' => Auth::user(),
        ]);

        return view('preferences');
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
            'accounts' => Account::getAccounts(),
        ]);

        return view('accounts');
    }

    /**
     * Display a listing of the resource.
     * GET /charts
     * @return Response
     */
//    public function charts()
//    {
//        JavaScript::put([
//            'me' => Auth::user()
//        ]);
//
//        return view('charts');
//    }

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
//    public function tags()
//    {
//        JavaScript::put([
//            'me' => Auth::user()
//        ]);
//
//        return view('tags');
//    }

}
