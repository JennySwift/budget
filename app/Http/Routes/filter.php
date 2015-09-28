<?php

Route::post('filter/transactions', 'FilterController@transactions');
Route::post('filter/basicTotals', 'FilterController@basicTotals');
Route::post('filter/graphTotals', 'FilterController@graphTotals');