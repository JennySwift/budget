<?php

return [

    /**
     * @var array
     */
    'defaults' => [
    "total" => [
        "in" => "",
        "out" => ""
    ],
    "types" => [
        "in" => [],
        "out" => []
    ],
    "accounts" => [
        "in" => [],
        "out" => []
    ],
    "singleDate" => [
        "inSql" => "",
        "outSql" => "",
    ],
    "fromDate" => [
        "inSql" => "",
        "outSql" => "",
    ],
    "toDate" => [
        "inSql" => "",
        "outSql" => "",
    ],
    "description" => [
        "in" => "",
        "out" => ""
    ],
    "merchant" => [
        "in" => "",
        "out" => ""
    ],
    "budgets" => [
        "in" => [
            "and" => [],
            "or" => []
        ],
        "out" => []
    ],
    "numBudgets" => [
        "in" => "all",
        "out" => "none"
    ],
    "invalidAllocation" => false,
    "reconciled" => "any",
    "offset" => 0,
    "numToFetch" => 30
]
];