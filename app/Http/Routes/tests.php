<?php

use App\Models\Budget;
use App\Models\Transaction;

//Route::get('/test', function () {

    /**
     * @VP: (less important)
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

Route::get('/test', function()
{
    $budget = Budget::first();
    //dd($budget);
    return $budget;
});
