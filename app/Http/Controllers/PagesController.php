<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Account;
use App\Models\Budget;
use App\Repositories\Transactions\FilterRepository;
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
     * @return Response
     */
    public function home(FilterRepository $filterRepository)
    {
        $remainingBalance = app('remaining-balance')->calculate();
        
        JavaScript::put([
            //It wouldn't work if I named it 'transactions', or 'totals'
            'accounts_response' => Account::getAccounts(),
//            'tags_response' => Tag::all(),//$tagsRepository->getTags(),
//            'totals_response' => [],//$totalsService->getBasicAndBudgetTotals(),
            'budgets' => Budget::forCurrentUser()->orderBy('name', 'asc')->get(),
            'filter_response' => $filterRepository->filterTransactions(),
            'me' => Auth::user(),

            'fixedBudgets' => $remainingBalance->fixedBudgetTotals->budgets,
            'flexBudgets' => $remainingBalance->flexBudgetTotals->budgets,
            'fixedBudgetTotals' => $remainingBalance->fixedBudgetTotals->toArray(),
            'flexBudgetTotals' => $remainingBalance->flexBudgetTotals->toArray(),
            'basicTotals' => $remainingBalance->basicTotals->toArray(),
            'remainingBalance' => $remainingBalance->amount,

            'env' => app()->env
        ]);

        return view('home');
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
     * Show the application dashboard to the user.
     * GET /budgets
     * @return Response
     */
    public function budgets()
    {
//        $budgets = Budget::forCurrentUser()->get();
//        $fixedBudgets = $budgets->filter(function($model){ return $model->type == 'fixed'; });
//        $flexBudgets = $budgets->filter(function($model){ return $model->type == 'flex'; });
//        $transactions = Transaction::forCurrentUser()->get();
//        $basicTotals = new BasicTotal($transactions);
//        $fixedBudgetTotals = new FixedBudgetTotal($fixedBudgets);
//        $flexBudgetTotals = new FlexBudgetTotal($flexBudgets);

        $remainingBalance = app('remaining-balance')->calculate();

        //dd($remainingBalance->flexBudgetTotals);

        JavaScript::put([
            'me' => Auth::user(),
            'fixedBudgets' => $remainingBalance->fixedBudgetTotals->budgets,
            'flexBudgets' => $remainingBalance->flexBudgetTotals->budgets,
            'fixedBudgetTotals' => $remainingBalance->fixedBudgetTotals->toArray(),
            'flexBudgetTotals' => $remainingBalance->flexBudgetTotals->toArray(),
            'basicTotals' => $remainingBalance->basicTotals->toArray(),
            'remainingBalance' => $remainingBalance->amount
//            'totals_response' => []//$totalsService->getBasicAndBudgetTotals()
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
