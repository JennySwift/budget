<?php

use App\Models\Transaction;

Route::get('/test', function () {
    $json = <<<EOF
{"filter": {

            "budget": {
                "in": "all",
                "out": ""
            },
            "total": {
                "in": "",
                "out": ""
            },
            "types": {
                "in": [],
                "out": []
            },
            "accounts": {
                "in": [],
                "out": []
            },
            "single_date": {
                "in": {
                    "user": "",
                    "sql": ""
                },
                "out": {
                    "user": "",
                    "sql": ""
                }
            },
            "from_date": {
                "in": {
                    "user": "",
                    "sql": ""
                },
                "out": {
                    "user": "",
                    "sql": ""
                }
            },
            "to_date": {
                "in": {
                    "user": "",
                    "sql": ""
                },
                "out": {
                    "user": "",
                    "sql": ""
                }
            },
            "description": {
                "in": "",
                "out": ""
            },
            "merchant": {
                "in": "",
                "out": ""
            },
            "tags": {
                "in": {
                    "and": [],
                    "or": []
                },
                "out": []
            },
            "reconciled": "any",
            "offset": 0,
            "num_to_fetch": 20
        }}
EOF;
    dd(json_decode($json, JSON_OBJECT_AS_ARRAY));
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

    dd('Test');
    $data = new FixedAndFlexData();
    dd($data->FLB);
    return $data->FB;


});

Route::get('/test', function()
{
    $transaction = Transaction::first();
    //dd($transaction);
    return $transaction;
});



//Route::get('/test', 'TransactionsController@filterTransactions');