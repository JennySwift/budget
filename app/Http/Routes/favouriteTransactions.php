<?php

// Resources
Route::resource('favouriteTransactions', 'FavouriteTransactionsController', ['only' => ['store', 'destroy']]);