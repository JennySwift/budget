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

        ],
        [
            'name' => 'transfer',
            'type' => 'transfer',
            'total' => '100',
            'fromAccount' => 'cash',
            'toAccount' => 'bank account',
            'merchant' => '',
            'description' => '',
            'budgets' => []

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

                if ($favourite['type'] === 'transfer') {
                    $newFavourite->fromAccount()->associate(Account::where('user_id', $user->id)->where('name', $favourite['fromAccount'])->first());
                    $newFavourite->toAccount()->associate(Account::where('user_id', $user->id)->where('name', $favourite['toAccount'])->first());
                }
                else {
                    $newFavourite->account()->associate(Account::where('user_id', $user->id)->where('name', $favourite['account'])->first());
                }

                $newFavourite->save();

                $budgetIds = [];
                foreach($favourite['budgets'] as $budgetName) {
                    $budgetId = Budget::where('user_id', $user->id)->where('name', $budgetName)->value('id');
                    $budgetIds[] = $budgetId;
                }

                $newFavourite->budgets()->attach($budgetIds);
            }

        }
	}

}