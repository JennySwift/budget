<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Transformers\SidebarTotalTransformer;
use App\Models\Account;
use App\Models\FavouriteTransaction;
use App\Models\Filter;
use App\Models\SavedFilter;
use App\Repositories\Budgets\BudgetsRepository;
use App\Repositories\Transactions\FavouriteTransactionsRepository;
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
     * @var FavouriteTransactionsRepository
     */
    private $favouriteTransactionsRepository;

    /**
     * Create a new controller instance.
     * @param BudgetsRepository $budgetsRepository
     * @param FavouriteTransactionsRepository $favouriteTransactionsRepository
     */
    public function __construct(BudgetsRepository $budgetsRepository, FavouriteTransactionsRepository $favouriteTransactionsRepository)
    {
        $this->middleware('auth');
        $this->budgetsRepository = $budgetsRepository;
        $this->favouriteTransactionsRepository = $favouriteTransactionsRepository;
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
////            //It wouldn't work if I named it 'transactions', or 'totals'
            'accounts_response' => Account::getAccounts(),
            'budgets' => $this->budgetsRepository->getBudgets(),
            'favouriteTransactions' => $this->favouriteTransactionsRepository->index(),
            'transactions' => $filter->getTransactions(),
            'filterBasicTotals' => $filter->getBasicTotals(),
            'savedFilters' => SavedFilter::forCurrentUser()->get(),
        ]);

        return view('pages/home');
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
            'fixedBudgets' => $remainingBalance->fixedBudgetTotals->budgets['data'],
            'fixedBudgetTotals' => $remainingBalance->fixedBudgetTotals->toArray(),
        ]);

        return view('pages/budgets/fixed');
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
            'flexBudgets' => $remainingBalance->flexBudgetTotals->budgets['data'],
            'flexBudgetTotals' => $remainingBalance->flexBudgetTotals->toArray(),
        ]);

        return view('pages/budgets/flex');
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
        ]);

        return view('pages/budgets/unassigned');
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function preferences()
    {
        JavaScript::put([
            'me' => Auth::user(),
        ]);

        return view('pages/preferences');
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function favouriteTransactions()
    {
        JavaScript::put([
            'me' => Auth::user(),
            'favouriteTransactions' => $this->favouriteTransactionsRepository->index(),
            'accounts' => Account::getAccounts(),
            'budgets' => $this->budgetsRepository->getBudgets(),
        ]);

        return view('pages/favourite-transactions');
    }
}
