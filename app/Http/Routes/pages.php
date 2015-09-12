<?php

/**
 * Pages
 * @TODO Regroup these methods in a single PagesController
 */
Route::get('/', 'PagesController@home');
Route::get('accounts', 'PagesController@accounts');
Route::get('budgets', 'PagesController@budgets');
Route::get('charts', 'PagesController@charts'); // Skipped in code review
Route::get('help', 'PagesController@help'); // Skipped in code review
Route::get('preferences', 'PagesController@preferences'); // Done after code review