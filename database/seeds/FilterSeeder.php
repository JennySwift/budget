<?php

use App\Models\SavedFilter;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

class FilterSeeder extends Seeder {

    private $defaults;

    public function run()
	{
        $this->defaults = Config::get('filters.defaults');

        $filters = [
            [
                'name' => 'bank account expenses',
                'filter' => [
                    'accounts' => [
                        'in' => [1],
                        'out' => []
                    ],
                    'types' => [
                        'in' => ['expense'],
                        'out' => []
                    ],
                    //These values needed for the JS
                    'displayFrom' => 1,
                    'displayTo' => 30
                ]
            ],
            [
                'name' => 'bank account expenses page 2',
                'filter' => [
                    'accounts' => [
                        'in' => [1],
                        'out' => []
                    ],
                    'types' => [
                        'in' => ['expense'],
                        'out' => []
                    ],
                    'offset' => 2,
                    'numToFetch' => 2,
                    //These values needed for the JS
                    'displayFrom' => 3,
                    'displayTo' => 4
                ]
            ],
            [
                'name' => 'cash account expenses',
                'filter' => [
                    'accounts' => [
                        'in' => [2],
                        'out' => []
                    ],
                    'types' => [
                        'in' => ['expense'],
                        'out' => []
                    ],
                    //These values needed for the JS
                    'displayFrom' => 1,
                    'displayTo' => 30
                ]
            ],
            [
                'name' => 'bank account income',
                'filter' => [
                    'accounts' => [
                        'in' => [1],
                        'out' => []
                    ],
                    'types' => [
                        'in' => ['income'],
                        'out' => []
                    ],
                    //These values needed for the JS
                    'displayFrom' => 1,
                    'displayTo' => 30
                ]
            ],
            [
                'name' => 'cash account income',
                'filter' => [
                    'accounts' => [
                        'in' => [2],
                        'out' => []
                    ],
                    'types' => [
                        'in' => ['income'],
                        'out' => []
                    ],
                    //These values needed for the JS
                    'displayFrom' => 1,
                    'displayTo' => 30
                ]
            ],
        ];

        $users = User::all();
        foreach($users as $user) {
            foreach($filters as $filter) {
                $mergedFilter = array_merge($this->defaults, $filter['filter']);

                $newFilter = new SavedFilter([
                    'name' => $filter['name'],
                    'filter' => $mergedFilter
                ]);

                $newFilter->user()->associate($user);
                $newFilter->save();

            }

        }
	}

}