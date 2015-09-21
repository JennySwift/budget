<?php

Route::post('select/allocationTotals', 'TransactionsController@getAllocationTotals');

// Resources
Route::get('totals', 'TotalsController@all');
Route::get('totals/sidebar', 'TotalsController@sidebar');
Route::get('totals/fixedBudget', 'TotalsController@fixedBudget');
Route::get('totals/flexBudget', 'TotalsController@flexBudget');
Route::get('totals/unassignedBudget', 'TotalsController@unassignedBudget');
