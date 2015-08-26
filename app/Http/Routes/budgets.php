<?php

/**
 * Budgets
 */

// Select
Route::post('select/allocationInfo', 'BudgetsController@getAllocationInfo');
Route::post('select/allocationTotals', 'TotalsController@getAllocationTotals');

// Insert
Route::post('insert/flexBudget', 'BudgetsController@insertFlexBudget');
Route::post('insert/budgetInfo', 'BudgetsController@insertBudgetInfo');