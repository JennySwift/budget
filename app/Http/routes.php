<?php
// my attempt at custom validation
Validator::extend('accepted_email', function ($attribute, $value, $parameters) {
    $accepted_emails = [
        //enter emails here
        'cheezyspaghetti@gmail.com',
        'cheezyspaghetti@optusnet.com.au',
        'nihantanu@gmail.com'
    ];
    $is_accepted = in_array($value, $accepted_emails);
    return $is_accepted;
});

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
    require app_path('Http/Routes/tags.php');
    require app_path('Http/Routes/users.php');
    require app_path('Http/Routes/preferences.php');
});

// Not so important routes
require app_path('Http/Routes/tests.php');
require app_path('Http/Routes/angular-directives.php');