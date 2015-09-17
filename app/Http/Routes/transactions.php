<?php

/**
 * Transactions
 */

// Select
Route::post('select/autocompleteTransaction', 'TransactionsController@autocompleteTransaction');

// Insert
//Route::post('insert/transaction', 'TransactionsController@insertTransaction');

// Update
Route::post('update/massDescription', 'TransactionsController@updateMassDescription');
//Route::post('update/transaction', 'TransactionsController@updateTransaction');
Route::post('updateReconciliation', 'TransactionsController@updateReconciliation');
Route::post('updateAllocationStatus', 'TransactionsController@updateAllocationStatus');

// PUT /budgets/{budgets}/transactions/{transactions}
Route::post('updateAllocation', 'TransactionsController@updateAllocation');

// Resources
Route::resource('transactions', 'TransactionsController', ['only' => ['show', 'store', 'update', 'destroy']]);