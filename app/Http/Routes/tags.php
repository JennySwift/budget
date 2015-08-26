<?php

/**
 * Tags
 */

// Select
Route::post('select/tags', 'TagsController@getTags');

// Update
//Route::post('update/budget', 'TagsController@createBudget');
// @TODO What is this one? Remove? Not Restful :/
Route::post('remove/budget', 'TagsController@removeBudget');
Route::post('update/tagName', 'TagsController@updateTagName');
Route::post('update/massTags', 'TagsController@updateMassTags');

// Delete
Route::post('delete/tag', 'TagsController@deleteTag');

// Resources
Route::resource('tags', 'TagsController', ['only' => ['show', 'store', 'update']]);