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
    "single_date" => [
        "inSql" => "",
        "outSql" => "",
    ],
    "from_date" => [
        "inSql" => "",
        "outSql" => "",
    ],
    "to_date" => [
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
    "reconciled" => "any",
    "offset" => 0,
    "numToFetch" => 30
]
];