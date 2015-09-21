<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Transformers\SidebarTotalTransformer;
use App\Models\Account;
use App\Models\Filter;
use App\Repositories\Budgets\BudgetsRepository;
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
     * @param Filter $filter
     * @return Response
     */
    public function home(Filter $filter)
    {
        JavaScript::put([
            'env' => app()->env,
            'me' => Auth::user(),
            'page' => 'home',
//            //It wouldn't work if I named it 'transactions', or 'totals'
            'accounts_response' => Account::getAccounts(),
            'budgets' => $this->budgetsRepository->getBudgets(),
            'filter_response' => $filter->filterTransactions()
        ]);

        return view('home');
    }

    /**
     * GET /fixed-budgets
     * @return Response
     */
    public function fixedBudgets()
    {
        $remainingBalance = app('remaining-balance')->calculate();

        JavaScript::put([
            'me' => Auth::user(),
            'page' => 'fixedBudgets',
            'fixedBudgets' => $remainingBalance->fixedBudgetTotals->budgets,
            'fixedBudgetTotals' => $remainingBalance->fixedBudgetTotals->toArray(),
        ]);

        return view('budgets/fixed');
    }

    /**
     * GET /flex-budgets
     * @return Response
     */
    public function flexBudgets()
    {
        $remainingBalance = app('remaining-balance')->calculate();

        JavaScript::put([
            'me' => Auth::user(),
            'page' => 'flexBudgets',
            'flexBudgets' => $remainingBalance->flexBudgetTotals->budgets,
            'flexBudgetTotals' => $remainingBalance->flexBudgetTotals->toArray(),
        ]);

        return view('budgets/flex');
    }

    /**
     * GET /unassigned-budgets
     * @return Response
     */
    public function unassignedBudgets()
    {
        $remainingBalance = app('remaining-balance')->calculate();

        JavaScript::put([
            'me' => Auth::user(),
            'page' => 'unassignedBudgets',
            'unassignedBudgets' => $remainingBalance->unassignedBudgetTotals->budgets,
            'unassignedBudgetTotals' => $remainingBalance->unassignedBudgetTotals->toArray(),
        ]);

        return view('budgets/unassigned');
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

}
