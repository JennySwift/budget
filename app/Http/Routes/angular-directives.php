<?php

/**
 * Angular directive templates
 */
Route::get('checkboxes', function () {
    return view('directives/CheckboxesTemplate');
});

Route::get('filter', function () {
    return view('directives/FilterTemplate');
});

Route::get('tag-autocomplete', function () {
    return view('directives/TagAutocompleteTemplate');
});

Route::get('totals-directive', function () {
    return view('directives/TotalsTemplate');
});

Route::get('transaction-autocomplete', function () {
    return view('directives/TransactionAutocompleteTemplate');
});

Route::get('filter-dropdowns', function () {
    return view('directives/FilterDropdownsTemplate');
});