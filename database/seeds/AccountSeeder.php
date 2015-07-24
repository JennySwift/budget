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
            $this->insertAccounts(1);
        }
        else {
            $this->insertAccounts(User::whereEmail('cheezyspaghetti@optusnet.com.au')->id);
        }

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

	private function insertAccounts($user_id)
	{
        $this->insertAccount($user_id, 'Bankwest');
        $this->insertAccount($user_id, 'nab');
        $this->insertAccount($user_id, 'cash');
	}

    private function insertAccount($user_id, $name)
    {
        $account = new Account(['name' => $name]);
        $account->user()->associate(User::find($user_id));
        $account->save();
    }

}