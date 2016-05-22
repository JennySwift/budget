<?php

return [
    'defaults' => [
        "colors" => [
            "income" => "#017d00",
            "expense" => "#fb5e52",
            "transfer" => "#fca700"
        ],
        "clearFields" => false,
        "dateFormat" => "DD/MM/YY",
        'autocompleteDescription' => true,
        'autocompleteMerchant' => true,

        'show' => [
            'totals' => [
                'credit' => true,
                'remainingFixedBudget' => true,
                'expensesWithoutBudget' => true,
                'expensesWithFixedBudgetBeforeStartingDate' => true,
                'expensesWithFixedBudgetAfterStartingDate' => true,
                'expensesWithFlexBudgetBeforeStartingDate' => true,
                'expensesWithFlexBudgetAfterStartingDate' => true,
                'savings' => true,
                'remainingBalance' => true,
                'debit' => true,
                'balance' => true,
                'reconciled' => true,
                'cumulativeFixedBudget' => true,
            ]
        ]
    ]

];