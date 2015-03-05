<?php

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

//I don't need to do it this way because HomeController comes with the middleware.
// Route::get('/', ['middleware' => 'auth', 'uses' => 'HomeController@index']);

Route::get('/', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('/db', function () {
	return App::environment(); 
});

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
