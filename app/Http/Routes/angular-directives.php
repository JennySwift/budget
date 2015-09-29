<?php

/**
 * Angular directive templates
 */

Route::get('filter', function () {
    return view('directives/FilterTemplate');
});

Route::get('filter-dropdowns', function () {
    return view('directives/FilterDropdownsTemplate');
});