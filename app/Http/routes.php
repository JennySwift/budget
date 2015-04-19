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

Route::get('/', 'HomeController@index');

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

// ====================================================================================
// ========================================ajax========================================
// ====================================================================================

// ========================================select========================================

Route::post('select/accounts', 'SelectController@accounts');
Route::post('select/filter', 'SelectController@filter');
Route::post('select/autocompleteTransaction', 'SelectController@autocompleteTransaction');
Route::post('select/tags', 'SelectController@tags');
Route::post('select/colors', 'SelectController@colors');
Route::post('select/duplicate-tag-check', 'SelectController@duplicateTagCheck');
Route::post('select/countTransactionsWithTag', 'SelectController@countTransactionsWithTag');
Route::post('select/allocationInfo', 'SelectController@allocationInfo');
Route::post('select/allocationTotals', 'SelectController@allocationTotals');

// ========================================insert========================================

Route::post('insert/tag', 'InsertController@tag');
Route::post('insert/account', 'InsertController@account');
Route::post('insert/flexBudget', 'InsertController@flexBudget');
Route::post('insert/budgetInfo', 'InsertController@budgetInfo');
Route::post('insert/transaction', 'InsertController@transaction');

// ========================================update========================================

Route::post('update/budget', 'UpdateController@budget');
Route::post('update/tagName', 'UpdateController@tagName');
Route::post('update/accountName', 'UpdateController@accountName');
Route::post('update/allocation', 'UpdateController@allocation');
Route::post('update/allocationStatus', 'UpdateController@allocationStatus');
Route::post('update/massTags', 'UpdateController@massTags');
Route::post('update/massDescription', 'UpdateController@massDescription');
Route::post('update/startingDate', 'UpdateController@startingDate');
Route::post('update/CSD', 'UpdateController@CSD');
Route::post('update/colors', 'UpdateController@colors');
Route::post('update/transaction', 'UpdateController@transaction');
Route::post('update/reconciliation', 'UpdateController@reconciliation');
Route::post('update/savingsTotal', 'UpdateController@savingsTotal');
Route::post('update/addFixedToSavings', 'UpdateController@addFixedToSavings');
Route::post('update/addPercentageToSavings', 'UpdateController@addPercentageToSavings');
Route::post('update/addPercentageToSavingsAutomatically', 'UpdateController@addPercentageToSavingsAutomatically');
Route::post('update/reverseAutomaticInsertIntoSavings', 'UpdateController@reverseAutomaticInsertIntoSavings');

// ========================================delete========================================

Route::post('delete/tag', function () {
	$tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
	DB::table('tags')->where('id', $tag_id)->delete();
	return $tag_id;
});
// Route::post('delete/tag', 'DeleteController@tag');
Route::post('delete/account', 'DeleteController@account');
Route::post('delete/item', 'DeleteController@item');
Route::post('delete/budget', 'DeleteController@budget');
Route::post('delete/transaction', 'DeleteController@transaction');

// ========================================totals========================================

// Route::post('totals/filter', 'TotalsController@filter');
Route::post('totals/basic', 'TotalsController@basic');
Route::post('totals/budget', 'TotalsController@budget');
