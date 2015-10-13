<?php

// Important application routes
require app_path('Http/Routes/auth.php');
require app_path('Http/Routes/pages.php');

// API
Route::group(['namespace' => 'API', 'prefix' => 'api'], function(){
    require app_path('Http/Routes/accounts.php');
    require app_path('Http/Routes/budgets.php');
    require app_path('Http/Routes/savings.php');
    require app_path('Http/Routes/totals.php');
    require app_path('Http/Routes/transactions.php');
    require app_path('Http/Routes/favouriteTransactions.php');
    require app_path('Http/Routes/autocomplete.php');
    require app_path('Http/Routes/filter.php');
    require app_path('Http/Routes/users.php');
    require app_path('Http/Routes/preferences.php');
    require app_path('Http/Routes/savedFilters.php');
});

// Not so important routes
require app_path('Http/Routes/tests.php');
require app_path('Http/Routes/angular-directives.php');