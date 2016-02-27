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
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        JavaScript::put([
            'env' => app()->env,
            'me' => Auth::user(),
            'page' => 'home',
        ]);

        return view('main.home');
    }
}
