<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;
use App\Models\Account;

class AccountSeeder extends Seeder {

    protected $accounts = ['bankwest', 'cash'];

	public function run()
	{
        $users = User::all();
        foreach($users as $user) {
            foreach($this->accounts as $account) {
                $tmp = new Account(['name' => $account]);
                $tmp->user()->associate($user);
                $tmp->save();
            }
        }
	}

}