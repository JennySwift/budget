<?php

/**
 * Totals
 */
// Select
Route::post('select/allocationTotals', 'TransactionsController@getAllocationTotals');

// Resources
Route::get('totals', 'TotalsController@all');

// ?
// Route::post('totals/basicAndBudget', 'TotalsController@index');