<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;
use App\Models\Account;

class AccountSeeder extends Seeder {

	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Account::truncate();
		
		$faker = Faker::create();

		/**
		 * Objective:
		 */
		
		Account::create([
			'name' => 'Bankwest',
			'user_id' => 1
		]);

		Account::create([
			'name' => 'nab',
			'user_id' => 1
		]);
		
		Account::create([
			'name' => 'Cash',
			'user_id' => 1
		]);

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}