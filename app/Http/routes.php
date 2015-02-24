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

// Route::get('/', ['middleware' => 'auth', 'uses' => 'WelcomeController@index']);
Route::get('/', 'WelcomeController@index');

Route::get('home', ['middleware' => 'auth', 'uses' => 'HomeController@index']);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('/db', function () {
	// return DB::select('select database();');
	// return DB::select('show tables;');
	// return DB::table('accounts')->get();
	// return DB::select('select * FROM accounts WHERE user_id = 1 ORDER BY name ASC');
	return App::environment(); 
});





Route::post('select/accounts', function () {
	return DB::select('select * FROM accounts WHERE user_id = 1 ORDER BY name ASC');
});

Route::post('select/ASR', 'TotalsController@ASR');
Route::post('select/filter-totals', 'TotalsController@filterTotals');
Route::post('select/basic-totals', 'TotalsController@basicTotals');
Route::post('select/budget-totals', 'TotalsController@budgetTotals');

Route::post('select/filter', 'SelectController@filter');
Route::post('select/tags', 'SelectController@tags');
Route::post('select/colors', 'SelectController@colors');
Route::post('select/duplicate-tag-check', 'SelectController@duplicateTagCheck');

Route::post('insert/tag', 'InsertController@tag');
