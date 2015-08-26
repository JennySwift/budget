<?php

/**
 * Accounts
 */

// Select
Route::post('select/accounts', 'AccountsController@getAccounts');

// Update
Route::post('update/accountName', 'AccountsController@updateAccountName');

// Delete
Route::post('delete/account', 'AccountsController@deleteAccount');

// Resource
Route::resource('accounts', 'AccountsController', ['only' => ['store']]);