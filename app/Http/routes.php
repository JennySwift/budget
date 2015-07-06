<?php

use App\Models\Transaction;
use Illuminate\Support\Facades\Route;

Route::get('/test', function()
{
    $transaction = Transaction::find(19);
    //dd($transaction);
    return Transaction::getTags(19);
});

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
Route::post('update/allocationStatus', 'TransactionsController@updateAllocationStatus');

Route::post('delete/transaction', 'TransactionsController@deleteTransaction');

/**
 * budgets
 */

Route::post('select/allocationInfo', 'BudgetsController@getAllocationInfo');
Route::post('select/allocationTotals', 'TotalsController@getAllocationTotals');

Route::post('insert/flexBudget', 'BudgetsController@insertFlexBudget');
Route::post('insert/budgetInfo', 'BudgetsController@insertBudgetInfo');

Route::post('update/allocation', 'BudgetsController@updateAllocation');

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

Route::post('update/CSD', 'TagsController@updateCSD');
Route::post('update/budget', 'TagsController@updateBudget');
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

