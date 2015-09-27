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

//This is just so I can write a test for the fixed budgets in TotalsTest.php
Route::get('fixedBudgets', 'BudgetsController@getFixedBudgets');

//This is just so I can write a test for the flex budgets in TotalsTest.php
Route::get('flexBudgets', 'BudgetsController@getFlexBudgets');

Route::resource('budgets', 'BudgetsController', ['only' => ['store', 'show', 'update', 'destroy']]);