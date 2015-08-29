<?php

Route::post('update/preferences', 'PreferencesController@updatePreferences');
Route::post('update/colors', 'PreferencesController@updateColors');
Route::post('insert/insertOrUpdateDateFormat', 'PreferencesController@insertOrUpdateDateFormat');