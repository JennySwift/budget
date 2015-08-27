<?php

/**
 * Accounts
 */
Route::resource('accounts', 'AccountsController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);