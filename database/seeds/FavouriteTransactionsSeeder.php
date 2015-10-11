<?php

use App\Models\FavouriteTransaction;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;
use App\Models\Account;

class FavouriteTransactionsSeeder extends Seeder {

    protected $favourites = [
        [
            'name' => 'foreign currency fee',
            'type' => 'expense',
            'description' => 'fee',
            'budget_ids' => []
        ],
        [
            'name' => 'private coaching',
            'type' => 'expense',
            'description' => 'coaching',
            'budget_ids' => [1,2]
        ],
        [
            'name' => 'groceries',
            'type' => 'expense',
            'description' => 'food',
            'budget_ids' => [1]

        ]
    ];

	public function run()
	{
        $users = User::all();
        foreach($users as $user) {
            foreach ($this->favourites as $favourite) {
                $newFavourite = new FavouriteTransaction([
                    'name' => $favourite['name'],
                    'type' => $favourite['type'],
                    'description' => $favourite['description']
                ]);

                $newFavourite->user()->associate($user);
                $newFavourite->save();
                $newFavourite->budgets()->attach($favourite['budget_ids']);
            }

        }
	}

}