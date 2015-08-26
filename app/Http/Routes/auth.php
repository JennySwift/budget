<?php
/**
* Authentication
*/
Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {

    Route::group(['middleware' => 'guest'], function () {
        // Login
        Route::get('login', [
            'as' => 'auth.login',
            'uses' => 'AuthController@getLogin'
        ]);
        Route::post('login', [
            'as' => 'auth.login.store',
            'before' => 'throttle:6,60',
            'uses' => 'AuthController@postLogin'
        ]);

        // Register
        Route::get('register', [
            'as' => 'auth.register',
            'uses' => 'AuthController@getRegister'
        ]);
        Route::post('register', [
            'as' => 'auth.register.store',
            'uses' => 'AuthController@postRegister'
        ]);
    });

    Route::group(['middleware' => 'auth'], function () {
        // Logout
        Route::get('logout', ['as' => 'auth.logout', 'uses' => 'AuthController@getLogout']);
    });

});

// Password reset
Route::controllers([
    'password' => 'Auth\PasswordController'
]);