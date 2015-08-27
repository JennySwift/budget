<?php

/**
 * Savings
 */

// Update
Route::group(['prefix' => 'savings'], function(){

    Route::put('set', 'SavingsController@set');
    Route::put('increase', 'SavingsController@increase');
    Route::put('decrease', 'SavingsController@decrease');

    // This could have been done with model binding,
    // which allows you to not fetch the savings account
    // object all the time in your controller
//    Route::put('increase/{savings}', 'SavingsController@increase');
//    Route::put('decrease/{savings}', 'SavingsController@decrease');

    // This right here is restful-ish :)
//    Route::put('updateSavingsTotal', 'SavingsController@updateSavingsTotal');
//    Route::put('updateSavingsTotalWithFixedAmount', 'SavingsController@updateSavingsTotalWithFixedAmount');
////    Route::put('updateSavingsTotalWithPercentage', 'SavingsController@updateSavingsTotalWithPercentage');
//    Route::put('updateSavingsTotalAutomatically', 'SavingsController@updateSavingsTotalAutomatically');
//    Route::post('reverseAutomaticInsertIntoSavings', 'SavingsController@reverseAutomaticInsertIntoSavings');

});