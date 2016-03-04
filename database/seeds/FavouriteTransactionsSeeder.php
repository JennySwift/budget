<?php

use App\Models\Budget;
use App\Models\FavouriteTransaction;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;
use App\Models\Account;

/**
 * Class FavouriteTransactionsSeeder
 */
class FavouriteTransactionsSeeder extends Seeder {

    /**
     * @var array
     */
    protected $favourites = [
        [
            'name' => 'foreign currency fee',
            'type' => 'expense',
            'total' => '',
            'account' => 'bank account',
            'merchant' => 'bank',
            'description' => 'fee',
            'budgets' => ['bank fees']
        ],
        [
            'name' => 'private coaching',
            'type' => 'expense',
            'total' => 50,
            'account' => 'bank account',
            'merchant' => 'coach',
            'description' => 'coaching',
            'budgets' => ['business']
        ],
        [
            'name' => 'groceries',
            'type' => 'expense',
            'total' => '',
            'account' => 'cash',
            'merchant' => 'grocery store',
            'description' => 'food',
            'budgets' => ['groceries']

        ]
    ];

    /**
     *
     */
    public function run()
	{
        $users = User::all();
        foreach($users as $user) {
            foreach ($this->favourites as $favourite) {
                $newFavourite = new FavouriteTransaction([
                    'name' => $favourite['name'],
                    'type' => $favourite['type'],
                    'description' => $favourite['description'],
                    'merchant' => $favourite['merchant'],
                    'total' => $favourite['total'],
                ]);

                $newFavourite->user()->associate($user);
                $newFavourite->account()->associate(Account::where('user_id', $user->id)->where('name', $favourite['account'])->first());
                $newFavourite->save();

                $budgetIds = [];
                foreach($favourite['budgets'] as $budgetName) {
                    $budgetIds[] = Budget::where('user_id', $user->id)->where('name', $budgetName)->pluck('id');
                }

                $newFavourite->budgets()->attach($budgetIds);
            }

        }
	}

}