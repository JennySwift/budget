<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;
use App\Models\Savings;

class SavingsSeeder extends Seeder {

	public function run()
	{
		Model::unguard();
		
		Savings::truncate();
		
		$faker = Faker::create();

		/**
		 * Objective:
		 */
		
		Savings::create([
			'amount' => 50,
			'user_id' => 1
		]);
	}

}