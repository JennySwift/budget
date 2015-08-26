<?php

// External route files
require app_path('Http/Routes/tests.php');
require app_path('Http/Routes/angular-directives.php');
require app_path('Http/Routes/pages.php');
require app_path('Http/Routes/auth.php');
require app_path('Http/Routes/resources.php');
require app_path('Http/Routes/transactions.php');
require app_path('Http/Routes/budgets.php');
require app_path('Http/Routes/accounts.php');
require app_path('Http/Routes/savings.php');


// Settings
Route::post('update/settings', 'SettingsController@updateSettings');

// Colors
Route::post('select/colors', 'ColorsController@getColors');
Route::post('update/colors', 'ColorsController@updateColors');

// Preferences
Route::post('insert/insertOrUpdateDateFormat', 'PreferencesController@insertOrUpdateDateFormat');

// Totals
//Route::post('totals/basicAndBudget', 'TotalsController@index');