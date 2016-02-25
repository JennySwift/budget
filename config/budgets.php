<?php

/**
 * This value needs to be 8 months ago in order for my tests to pass.
 * So if it is now Jan 2016, $starting date should be in May 2015
 */
$startingDate = '2015-06-01';

return [
    'types' => ['fixed', 'flex', 'unassigned'],
    'seeder1' => [
        [
            'type' => 'unassigned',
            'name' => 'bank fees'
        ],
        [
            'type' => 'fixed',
            'name' => 'business',
            'amount' => 100,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'fixed',
            'name' => 'groceries',
            'amount' => 100,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'busking',
            'amount' => 10.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'eating out',
            'amount' => 10.00,
            'starting_date' => $startingDate
        ],
    ],
    'seeder2' => [
        [
            'type' => 'unassigned',
            'name' => 'bank fees'
        ],
        [
            'type' => 'unassigned',
            'name' => 'something'
        ],
        [
            'type' => 'fixed',
            'name' => 'business',
            'amount' => 100,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'fixed',
            'name' => 'groceries',
            'amount' => 100,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'fixed',
            'name' => 'rent',
            'amount' => 50,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'fixed',
            'name' => 'licenses',
            'amount' => 20,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'fixed',
            'name' => 'insurance',
            'amount' => 100,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'fixed',
            'name' => 'conferences',
            'amount' => 100,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'fixed',
            'name' => 'car',
            'amount' => 100,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'fixed',
            'name' => 'mobile phone',
            'amount' => 20,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'fixed',
            'name' => 'petrol',
            'amount' => 50,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'fixed',
            'name' => 'sport',
            'amount' => 20,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'busking',
            'amount' => 10.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'eating out',
            'amount' => 10.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'entertainment',
            'amount' => 5.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'recreation',
            'amount' => 20.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'holidays',
            'amount' => 50.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'gifts',
            'amount' => 10.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'books',
            'amount' => 40.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'clothes',
            'amount' => 50.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'church',
            'amount' => 50.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'equipment',
            'amount' => 50.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'guitar',
            'amount' => 100.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'health',
            'amount' => 50.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'miscellaneous',
            'amount' => 50.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'music',
            'amount' => 50.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'superannuation',
            'amount' => 50.00,
            'starting_date' => $startingDate
        ],
        [
            'type' => 'flex',
            'name' => 'tax',
            'amount' => 50.00,
            'starting_date' => $startingDate
        ]
    ]
];