<?php

/**
 * Pages
 */
Route::get('/', 'HomeController@index');
Route::get('/budgets', 'BudgetsController@index');
Route::get('/tags', 'TagsController@index');
Route::get('/accounts', 'AccountsController@index');
Route::get('/charts', 'ChartsController@index');