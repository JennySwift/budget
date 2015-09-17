<?php

// PUT /budgets/{budgets}/transactions/{transactions}
Route::post('updateAllocation', 'TransactionsController@updateAllocation');

// Resources
Route::resource('transactions', 'TransactionsController', ['only' => ['show', 'store', 'update', 'destroy']]);