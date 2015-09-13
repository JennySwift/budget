<?php

///**
// * Tags
// */
//// @TODO Temporary route!!
//Route::put('tags/{tags}/update', 'TagsController@updateTagName');
//
//// Delete
//// @TODO Gonna have to move away too :(
//Route::post('remove/budget', 'TagsController@removeBudget');
//
//// Resources
//// @TODO Take care of the update method also!
//Route::resource('tags', 'TagsController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);

Route::resource('budgets', 'BudgetsController', ['only' => ['store', 'show', 'update', 'destroy']]);