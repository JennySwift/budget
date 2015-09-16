<?php

/**
 * Totals
 */
// Select
Route::post('select/allocationTotals', 'TransactionsController@getAllocationTotals');

// Resources
Route::get('totals', 'TotalsController@all');
Route::get('totals/sidebar', 'TotalsController@sidebar');

// ?
// Route::post('totals/basicAndBudget', 'TotalsController@index');