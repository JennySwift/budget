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
    require app_path('Http/Routes/tags.php');
    require app_path('Http/Routes/users.php');

    // Settings
    Route::post('update/settings', 'SettingsController@updateSettings');

    // Colors
    Route::post('select/colors', 'ColorsController@getColors');
    Route::post('update/colors', 'ColorsController@updateColors');

    // Preferences
    Route::post('insert/insertOrUpdateDateFormat', 'PreferencesController@insertOrUpdateDateFormat');
});

// Not so important routes
require app_path('Http/Routes/tests.php');
require app_path('Http/Routes/angular-directives.php');