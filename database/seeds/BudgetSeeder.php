<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Budget;

class BudgetSeeder extends Seeder {

	protected $budgets;

	public function __construct() {
		$this->budgets = [
			[
				'type' => 'fixed',
				'name' => 'Petrol',
				'amount' => 100,
				'starting_date' => Carbon::yesterday()->format('Y-m-d')
			],
			[
				'type' => 'flex',
				'name' => 'Eating out',
				'amount' => 50.00,
				'starting_date' => Carbon::yesterday()->format('Y-m-d')
			]
		];
	}

	public function run()
	{
		$users = User::all();

		foreach($users as $user) {
			foreach($this->budgets as $budget) {
				$tmp = new Budget($budget);
				$tmp->user()->associate($user);
				$tmp->save();
			}
		}
	}

}