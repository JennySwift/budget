<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Budget;

class BudgetSeeder extends Seeder {

	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		Model::unguard();

		Budget::truncate();

		/**
		 * Objective:
		 */
		
		Budget::create([
			'type' => 'fixed',
		]);
		Budget::create([
			'type' => 'flex',
		]);

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}