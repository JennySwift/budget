<?php

use App\Models\Tag;
use App\Models\Transaction;
use App\Repositories\Transactions\TransactionsRepository;
use App\Totals\BudgetTable;
use App\Totals\FixedAndFlexData;
use App\Totals\RB;
use App\Totals\TotalsService;
use Illuminate\Support\Facades\Route;

//Route::get('/test', function () {
    /**
     * @VP:
     * How to I inject something into my routes file?
     * So I can do return $this->totalsService->getBasicAndBudgetTotals();
     */

//    $budgetTable = new BudgetTable('fixed');
//    return $budgetTable->getTagsWithFixedBudget();

//    $data = new FixedAndFlexData();
//    dd($data->FB->tags);
//
//    $tag = Tag::find(1);
//    $tag->spentBeforeSD;
//    $tag->sum;
//    return $tag;

//});

Route::get('/test', function()
{
    $transaction = Transaction::find(4);
    $tag = Tag::find(3);
    return $transaction->updateAllocatedPercent(20, $tag);
});


//Route::get('/test', 'TransactionsController@whatInTheWorldIsGoingOnHere');

/**
 * Angular directive templates
 */

Route::get('checkboxes', function () {
    return view('directives/CheckboxesTemplate');
});

Route::get('filter', function () {
    return view('directives/FilterTemplate');
});

Route::get('tag-autocomplete', function () {
    return view('directives/TagAutocompleteTemplate');
});

Route::get('totals-directive', function () {
    return view('directives/TotalsTemplate');
});

Route::get('transaction-autocomplete', function () {
    return view('directives/TransactionAutocompleteTemplate');
});

Route::get('filter-dropdowns', function () {
    return view('directives/FilterDropdownsTemplate');
});

//Route::get('filter/types', function () {
//    return view('templates/home/filter/types');
//});


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


/**
 * Authentication
 */

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {

    Route::group(['middleware' => 'guest'], function () {
        // Login
        Route::get('login', ['as' => 'auth.login', 'uses' => 'AuthController@getLogin']);
        Route::post('login',
            ['as' => 'auth.login.store', 'before' => 'throttle:6,60', 'uses' => 'AuthController@postLogin']);

        // Register
        Route::get('register', ['as' => 'auth.register', 'uses' => 'AuthController@getRegister']);
        Route::post('register', ['as' => 'auth.register.store', 'uses' => 'AuthController@postRegister']);
    });

    Route::group(['middleware' => 'auth'], function () {
        // Logout
        Route::get('logout', ['as' => 'auth.logout', 'uses' => 'AuthController@getLogout']);
    });

});

Route::controllers([
    // 'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

/**
 * Resources
 */

Route::resource('tags', 'TagsController', ['only' => ['show', 'update']]);
Route::resource('transactions', 'TransactionsController', ['only' => ['show', 'update']]);
Route::resource('totals', 'TotalsController', ['only' => ['index']]);
Route::resource('help', 'HelpController', ['only' => ['index']]);
Route::resource('user', 'UsersController', ['only' => ['show', 'destroy']]);

/**
 * Ajax
 */

/**
 * Settings
 */
Route::post('update/settings', 'SettingsController@updateSettings');

/**
 * transactions
 */

Route::post('select/filter', 'TransactionsController@filterTransactions');
Route::post('select/autocompleteTransaction', 'TransactionsController@autocompleteTransaction');
Route::post('select/countTransactionsWithTag', 'TransactionsController@countTransactionsWithTag');

Route::post('insert/transaction', 'TransactionsController@insertTransaction');

Route::post('update/massDescription', 'TransactionsController@updateMassDescription');
//Route::post('update/transaction', 'TransactionsController@updateTransaction');
Route::post('update/reconciliation', 'TransactionsController@updateReconciliation');
Route::post('update/allocationStatus', 'TransactionsController@updateAllocationStatus');
Route::post('update/allocation', 'TransactionsController@updateAllocation');
Route::post('delete/transaction', 'TransactionsController@deleteTransaction');

/**
 * budgets
 */

Route::post('select/allocationInfo', 'BudgetsController@getAllocationInfo');
Route::post('select/allocationTotals', 'TotalsController@getAllocationTotals');

Route::post('insert/flexBudget', 'BudgetsController@insertFlexBudget');
Route::post('insert/budgetInfo', 'BudgetsController@insertBudgetInfo');

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

//Route::post('totals/basicAndBudget', 'TotalsController@index');

/**
 * preferences
 */

Route::post('insert/insertOrUpdateDateFormat', 'PreferencesController@insertOrUpdateDateFormat');

