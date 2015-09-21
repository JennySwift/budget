<?php

/**
 * Pages
 * @TODO Regroup these methods in a single PagesController
 */
Route::get('/', 'PagesController@home');
Route::get('/home', 'PagesController@home');
Route::get('accounts', 'PagesController@accounts');
Route::get('budgets/fixed', 'PagesController@fixedBudgets');
Route::get('budgets/flex', 'PagesController@flexBudgets');
Route::get('budgets/unassigned', 'PagesController@unassignedBudgets');
Route::get('help', 'PagesController@help'); // Skipped in code review
Route::get('preferences', 'PagesController@preferences'); // Done after code review