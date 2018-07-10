<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'API', 'middleware' => 'auth:api'], function () {
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
        Route::get('transactions', 'FilterController@transactions');
        Route::post('basicTotals', 'FilterController@basicTotals');
        Route::post('graphTotals', 'FilterController@graphTotals');
    });

    Route::get('environment', function () {
//        dd(app()->environment());
        return response(app()->environment(), Response::HTTP_OK);
    });

});