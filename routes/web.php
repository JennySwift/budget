<?php

//This line made test error
//use JavaScript;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/', ['middleware' => 'auth', function () {
    JavaScript::put([
        'env' => app()->env,
        'me' => Auth::user(),
    ]);

    return view('main.home');
}]);
