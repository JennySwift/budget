<?php

/**
 * Pages
 * @TODO Regroup these methods in a single PagesController
 */
Route::get('/', 'HomeController@index');
Route::get('budgets', 'BudgetsController@index');
Route::get('tags', 'TagsController@index');
Route::get('accounts', 'AccountsController@index');
Route::get('charts', 'ChartsController@index');
Route::get('help', 'HelpController@index');