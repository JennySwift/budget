<?php

//use App\Models\Transaction;

//Route::get('/test', function () {

    /**
     * @VP:
     * How to I inject something into my routes file?
     * So I can do return $this->totalsService->getBasicAndBudgetTotals();
     */

//    $budgetTable = new BudgetTable('fixed');
//    return $budgetTable->getTagsWithFixedBudget();

//    $data = new FixedAndFlexData();
//    dd($data->FB->tags);
//
//    $tag = Tag::find(1);
//    $tag->spentBeforeSD;
//    $tag->sum;
//    return $tag;

//});

//Route::post('/test', function(TransactionsController $transactionsController)
//{
//    $filter = [
//        "budget" => [],
//        "total" => "",
//        "types" => [],
//        "accounts" => [],
//        "single_date" => "",
//        "from_date" => "",
//        "to_date" => "",
//        "description" => "",
//        "merchant" => "",
//        "tags" => [],
//        "reconciled" => "any",
//        "offset" => 0,
//        "num_to_fetch" => 20
//    ];
//
//    return $transactionsController->filterTransactions($filter);
//});

//    dd('Test');
//    $data = new FixedAndFlexData();
//    dd($data->FLB);
//    return $data->FB;

//
//});

Route::get('/test', function()
{
    $this->app->make('App\Models\Totals\RemainingBalance')->calculate();
});



//Route::get('/test', 'TransactionsController@filterTransactions');