<?php

/**
 * Transactions
 */

// Select
Route::post('select/filter', 'TransactionsController@filterTransactions');
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

// Delete
Route::post('delete/transaction', 'TransactionsController@deleteTransaction');

// Resources
Route::resource('transactions', 'TransactionsController', ['only' => ['show', 'store', 'update']]);