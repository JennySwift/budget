<?php

Route::resource('savedFilters', 'SavedFiltersController', ['only' => ['store']]);
