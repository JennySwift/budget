<?php

//use JavaScript;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/', ['middleware' => 'auth', function () {
    //This caused an vue warning after upgrading
//    JavaScript::put([
//        'env' => app()->env,
//        'me' => Auth::user(),
//        'page' => 'home',
//    ]);

    return view('main.home');
}]);

//Route::get('/home', 'PagesController@home');

// API
Route::group(['namespace' => 'API', 'prefix' => 'api', 'middleware' => ['auth', 'web']], function(){
    Route::get('users/current', 'UsersController@showCurrentUser');

    Route::resource('accounts', 'AccountsController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('budgets', 'BudgetsController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
    Route::resource('savedFilters', 'SavedFiltersController', ['only' => ['index', 'store', 'destroy']]);
    Route::resource('transactions', 'TransactionsController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('budgets.transactions', 'BudgetTransactionController', ['only' => ['update']]);
    Route::resource('favouriteTransactions', 'FavouriteTransactionsController', ['only' => ['index', 'store', 'update', 'destroy']]);
    Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'destroy']]);
    Route::resource('feedback', 'FeedbackController', ['only' => ['store']]);

    Route::group(['prefix' => 'savings'], function ()
    {
        Route::put('set', 'SavingsController@set');
        Route::put('increase', 'SavingsController@increase');
        Route::put('decrease', 'SavingsController@decrease');
    });

    Route::group(['prefix' => 'totals'], function ()
    {
        Route::get('/', 'TotalsController@all');
        Route::get('sidebar', 'TotalsController@sidebar');
        Route::get('fixedBudget', 'TotalsController@fixedBudget');
        Route::get('flexBudget', 'TotalsController@flexBudget');
        Route::get('unassignedBudget', 'TotalsController@unassignedBudget');
        Route::get('spentOnBudgets', 'TotalsController@spentOnBudgets');
    });

    Route::group(['prefix' => 'filter'], function ()
    {
        Route::post('transactions', 'FilterController@transactions');
        Route::post('basicTotals', 'FilterController@basicTotals');
        Route::post('graphTotals', 'FilterController@graphTotals');
    });

});

Route::get('/home', 'HomeController@index')->name('home');
