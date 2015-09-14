<?php


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

use App\Models\Budget;

Route::get('/test', function()
{
    $budget = Budget::first();
    //dd($budget);
    return $budget;
});
