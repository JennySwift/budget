<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Budget;

class BudgetSeeder extends Seeder {

	protected $budgets;
    protected $startingDate = '2015-01-01';

	public function __construct() {
		$this->budgets = [
			[
				'type' => 'fixed',
				'name' => 'groceries',
				'amount' => 100,
				'starting_date' => $this->startingDate
			],
            [
                'type' => 'fixed',
                'name' => 'rent',
                'amount' => 50,
                'starting_date' => $this->startingDate
            ],
            [
                'type' => 'fixed',
                'name' => 'licenses',
                'amount' => 20,
                'starting_date' => $this->startingDate
            ],
            [
                'type' => 'fixed',
                'name' => 'insurance',
                'amount' => 100,
                'starting_date' => $this->startingDate
            ],
            [
                'type' => 'fixed',
                'name' => 'conferences',
                'amount' => 100,
                'starting_date' => $this->startingDate
            ],
            [
                'type' => 'fixed',
                'name' => 'car',
                'amount' => 100,
                'starting_date' => $this->startingDate
            ],
            [
                'type' => 'fixed',
                'name' => 'mobile phone',
                'amount' => 20,
                'starting_date' => $this->startingDate
            ],
            [
                'type' => 'fixed',
                'name' => 'petrol',
                'amount' => 50,
                'starting_date' => $this->startingDate
            ],
            [
                'type' => 'fixed',
                'name' => 'sport',
                'amount' => 20,
                'starting_date' => $this->startingDate
            ],
			[
				'type' => 'flex',
				'name' => 'eating out',
				'amount' => 10.00,
				'starting_date' => $this->startingDate
			],
            [
                'type' => 'flex',
                'name' => 'entertainment',
                'amount' => 10.00,
                'starting_date' => $this->startingDate
            ],
            [
                'type' => 'flex',
                'name' => 'recreation',
                'amount' => 20.00,
                'starting_date' => $this->startingDate
            ],
            [
                'type' => 'flex',
                'name' => 'holidays',
                'amount' => 50.00,
                'starting_date' => $this->startingDate
            ],
//            [
//                'type' => 'flex',
//                'name' => 'gifts',
//                'amount' => 10.00,
//                'starting_date' => $this->startingDate
//            ],
//            [
//                'type' => 'flex',
//                'name' => 'books',
//                'amount' => 40.00,
//                'starting_date' => $this->startingDate
//            ],
//            [
//                'type' => 'flex',
//                'name' => 'clothes',
//                'amount' => 50.00,
//                'starting_date' => $this->startingDate
//            ],
//            [
//                'type' => 'flex',
//                'name' => 'church',
//                'amount' => 50.00,
//                'starting_date' => $this->startingDate
//            ],
//            [
//                'type' => 'flex',
//                'name' => 'equipment',
//                'amount' => 50.00,
//                'starting_date' => $this->startingDate
//            ],
//            [
//                'type' => 'flex',
//                'name' => 'guitar',
//                'amount' => 100.00,
//                'starting_date' => $this->startingDate
//            ],
//            [
//                'type' => 'flex',
//                'name' => 'health',
//                'amount' => 50.00,
//                'starting_date' => $this->startingDate
//            ],
//            [
//                'type' => 'flex',
//                'name' => 'miscellaneous',
//                'amount' => 50.00,
//                'starting_date' => $this->startingDate
//            ],
//            [
//                'type' => 'flex',
//                'name' => 'music',
//                'amount' => 50.00,
//                'starting_date' => $this->startingDate
//            ],
//            [
//                'type' => 'flex',
//                'name' => 'superannuation',
//                'amount' => 50.00,
//                'starting_date' => $this->startingDate
//            ],
//            [
//                'type' => 'flex',
//                'name' => 'tax',
//                'amount' => 50.00,
//                'starting_date' => $this->startingDate
//            ]
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