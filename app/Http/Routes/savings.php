<?php

/**
 * Savings
 */

// Update
Route::post('update/savingsTotal', 'SavingsController@updateSavingsTotal');
Route::post('update/addFixedToSavings', 'SavingsController@addFixedToSavings');
Route::post('update/addPercentageToSavings', 'SavingsController@addPercentageToSavings');
Route::post('update/addPercentageToSavingsAutomatically', 'SavingsController@addPercentageToSavingsAutomatically');
Route::post('update/reverseAutomaticInsertIntoSavings', 'SavingsController@reverseAutomaticInsertIntoSavings');