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
        "in" => [
            "user" => "",
            "sql" => ""
        ],
        "out" => [
            "user" => "",
            "sql" => ""
        ],
    ],
    "from_date" => [
        "in" => [
            "user" => "",
            "sql" => ""
        ],
        "out" => [
            "user" => "",
            "sql" => ""
        ],
    ],
    "to_date" => [
        "in" => [
            "user" => "",
            "sql" => ""
        ],
        "out" => [
            "user" => "",
            "sql" => ""
        ],
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
    "num_to_fetch" => 30
]
];