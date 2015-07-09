<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{	
		$this->call('UserSeeder');

		$this->call('SavingsSeeder');

		$this->call('ColorSeeder');

		$this->call('BudgetSeeder');

		$this->call('AccountSeeder');

		$this->call('TagSeeder');

//		$this->call('TransactionTagSeeder');

		$this->call('TransactionSeeder');
	}

}
