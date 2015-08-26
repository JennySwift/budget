<?php

/**
 * Users
 */
Route::resource('user', 'UsersController', ['only' => ['show', 'destroy']]);