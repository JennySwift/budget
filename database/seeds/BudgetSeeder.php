<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Budget;

class BudgetSeeder extends Seeder {

//	protected $budgets;
//    protected $startingDate = '2015-01-01';

	public function __construct() {

    }

	public function run()
	{
		$users = User::all();

		foreach($users as $user) {
            /**
             * @VP:
             * Why didn't this syntax work?
             * $budgets = Config::get('budgets.seeder{$user->id}');
             */

            $budgets = Config::get('budgets.seeder' . $user->id);

			foreach($budgets as $budget) {
				$tmp = new Budget($budget);
				$tmp->user()->associate($user);
				$tmp->save();
			}
		}
	}
}