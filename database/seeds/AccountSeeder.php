<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;
use App\Models\Account;

class AccountSeeder extends Seeder {

	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

        if (app()->env === 'local') {
            Account::truncate();
            $this->insertAccounts(User::whereEmail('cheezyspaghetti@gmail.com')->first());
        }
        else {
            $this->insertAccounts(User::whereEmail('cheezyspaghetti@optusnet.com.au')->first());
        }

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

	private function insertAccounts($user)
	{
        $this->insertAccount($user, 'Bankwest');
        $this->insertAccount($user, 'nab');
        $this->insertAccount($user, 'cash');
	}

    private function insertAccount($user, $name)
    {
        $account = new Account(['name' => $name]);
        $account->user()->associate($user);
        $account->save();
    }

}