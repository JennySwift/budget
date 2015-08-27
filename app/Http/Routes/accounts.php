<?php

/**
 * Accounts
 */

use App\Models\Account;

// Model binding
Route::bind('accounts', function($id)
{
    return Account::forCurrentUser()->findOrFail($id);
});

// Resource
Route::resource('accounts', 'AccountsController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);