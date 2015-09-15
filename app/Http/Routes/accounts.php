<?php

/**
 * Accounts
 */
Route::resource('accounts', 'AccountsController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
//Route::resource('accounts.transactions', 'AccountsTransactionsController', ['only' => ['store']]);