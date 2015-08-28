<?php

/**
 * Totals
 */
// Select
Route::post('select/allocationTotals', 'TotalsController@getAllocationTotals');

// Resources
Route::resource('totals', 'TotalsController', ['only' => ['index']]);

// ?
// Route::post('totals/basicAndBudget', 'TotalsController@index');