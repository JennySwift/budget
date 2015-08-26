<?php

/**
 * Budgets
 */

// Select
Route::post('select/allocationInfo', 'BudgetsController@getAllocationInfo');

// Insert
Route::post('insert/flexBudget', 'BudgetsController@insertFlexBudget');
Route::post('insert/budgetInfo', 'BudgetsController@insertBudgetInfo');