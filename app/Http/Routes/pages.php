<?php

/**
 * Pages
 * @TODO Regroup these methods in a single PagesController
 */
Route::get('/', 'PagesController@home');
Route::get('accounts', 'PagesController@accounts');
Route::get('budgets', 'PagesController@budgets');
Route::get('charts', 'PagesController@charts');
Route::get('help', 'PagesController@help');
Route::get('tags', 'PagesController@tags');
