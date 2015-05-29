<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * Home page
 */

Route::get('/', 'HomeController@index');
Route::get('/budgets', 'BudgetsController@index');
Route::get('/tags', 'TagsController@index');
Route::get('/accounts', 'AccountsController@index');
Route::get('/charts', 'ChartsController@index');

// Route::get('/settings', function()
// {
//     return view('settings');
// });

// Route::get('/test', function()
// {
//     return view('test');
// });



/**
 * Authentication
 */

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function(){

    Route::group(['middleware' => 'guest'], function(){
        // Login
        Route::get('login', ['as' => 'auth.login', 'uses' => 'AuthController@getLogin']);
        Route::post('login', ['as' => 'auth.login.store', 'before' => 'throttle:6,60', 'uses' => 'AuthController@postLogin']);

        // Register
        Route::get('register', ['as' => 'auth.register', 'uses' => 'AuthController@getRegister']);
        Route::post('register', ['as' => 'auth.register.store', 'uses' => 'AuthController@postRegister']);
    });

    Route::group(['middleware' => 'auth'], function(){
        // Logout
        Route::get('logout', ['as' => 'auth.logout', 'uses' => 'AuthController@getLogout']);
    });

});

Route::controllers([
	// 'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

/**
 * Ajax
 */

/**
 * transactions
 */

Route::post('select/filter', 'TransactionsController@filterTransactions');
Route::post('select/autocompleteTransaction', 'TransactionsController@autocompleteTransaction');
Route::post('select/countTransactionsWithTag', 'TransactionsController@countTransactionsWithTag');

Route::post('insert/transaction', 'TransactionsController@insertTransaction');

Route::post('update/massDescription', 'TransactionsController@updateMassDescription');
Route::post('update/transaction', 'TransactionsController@updateTransaction');
Route::post('update/reconciliation', 'TransactionsController@updateReconciliation');

Route::post('delete/transaction', 'TransactionsController@deleteTransaction');

/**
 * budgets
 */

Route::post('select/allocationInfo', 'BudgetsController@getAllocationInfo');
Route::post('select/allocationTotals', 'TotalsController@getAllocationTotals');

Route::post('insert/flexBudget', 'BudgetsController@insertFlexBudget');
Route::post('insert/budgetInfo', 'BudgetsController@insertBudgetInfo');

Route::post('update/budget', 'BudgetsController@updateBudget');
Route::post('update/allocation', 'BudgetsController@updateAllocation');
Route::post('update/allocationStatus', 'BudgetsController@updateAllocationStatus');
Route::post('update/startingDate', 'BudgetsController@updateStartingDate');
Route::post('update/CSD', 'BudgetsController@updateCSD');

/**
 * accounts
 */

Route::post('select/accounts', 'AccountsController@getAccounts');

Route::post('insert/account', 'AccountsController@insertAccount');

Route::post('update/accountName', 'AccountsController@updateAccountName');

Route::post('delete/account', 'AccountsController@deleteAccount');

/**
 * tags
 */

Route::post('select/tags', 'TagsController@getTags');
Route::post('select/duplicate-tag-check', 'TagsController@duplicateTagCheck');

Route::post('insert/tag', 'TagsController@insertTag');

Route::post('update/tagName', 'TagsController@updateTagName');
Route::post('update/massTags', 'TagsController@updateMassTags');
Route::post('delete/tag', 'TagsController@deleteTag');

/**
 * colors
 */

Route::post('select/colors', 'ColorsController@getColors');

Route::post('update/colors', 'ColorsController@updateColors');

/**
 * savings
 */

Route::post('update/savingsTotal', 'SavingsController@updateSavingsTotal');
Route::post('update/addFixedToSavings', 'SavingsController@addFixedToSavings');
Route::post('update/addPercentageToSavings', 'SavingsController@addPercentageToSavings');
Route::post('update/addPercentageToSavingsAutomatically', 'SavingsController@addPercentageToSavingsAutomatically');
Route::post('update/reverseAutomaticInsertIntoSavings', 'SavingsController@reverseAutomaticInsertIntoSavings');

/**
 * totals
 */

Route::post('totals/basic', 'TotalsController@getBasicTotals');
Route::post('totals/budget', 'TotalsController@getBudgetTotals');

/**
 * preferences
 */

Route::post('insert/insertOrUpdateDateFormat', 'PreferencesController@insertOrUpdateDateFormat');

