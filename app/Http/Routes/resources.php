<?php

/**
 * Resources
 */
Route::resource('tags', 'TagsController', ['only' => ['show', 'store', 'update']]);
Route::resource('transactions', 'TransactionsController', ['only' => ['show', 'update']]);
Route::resource('totals', 'TotalsController', ['only' => ['index']]);
Route::resource('help', 'HelpController', ['only' => ['index']]);
Route::resource('user', 'UsersController', ['only' => ['show', 'destroy']]);
Route::resource('accounts', 'AccountsController', ['only' => ['store']]);