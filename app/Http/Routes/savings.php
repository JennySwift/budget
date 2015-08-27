<?php

/**
 * Savings
 */

// Update
Route::group(['prefix' => 'savings'], function(){

    // This right here is restful-ish :)
    Route::put('updateSavingsTotal', 'SavingsController@updateSavingsTotal');
    Route::post('addFixedToSavings', 'SavingsController@addFixedToSavings');
    Route::post('addPercentageToSavings', 'SavingsController@addPercentageToSavings');
    Route::post('addPercentageToSavingsAutomatically', 'SavingsController@addPercentageToSavingsAutomatically');
    Route::post('reverseAutomaticInsertIntoSavings', 'SavingsController@reverseAutomaticInsertIntoSavings');

});