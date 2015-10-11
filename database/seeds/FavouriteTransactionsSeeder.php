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
            'description' => 'fee'
        ],
        [
            'name' => 'private coaching',
            'type' => 'expense',
            'description' => 'coaching'
        ],
        [
            'name' => 'groceries',
            'type' => 'expense',
            'description' => 'food'

        ]
    ];

	public function run()
	{
        $users = User::all();
        foreach($users as $user) {
            foreach ($this->favourites as $favourite) {
                $favourite = new FavouriteTransaction([
                    'name' => $favourite['name'],
                    'type' => $favourite['type'],
                    'description' => $favourite['description']
                ]);

                $favourite->user()->associate($user);
                $favourite->save();
            }

        }
	}

}