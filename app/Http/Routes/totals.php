<?php

/**
 * Totals
 */

// Select
Route::post('select/allocationTotals', 'TotalsController@getAllocationTotals');

// Resources
Route::resource('totals', 'TotalsController', ['only' => ['index']]);