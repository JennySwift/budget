<?php

/**
 * Accounts
 */

// Delete
Route::post('delete/account', 'AccountsController@deleteAccount');

// Resource
Route::resource('accounts', 'AccountsController', ['only' => ['index', 'show', 'store', 'update']]);