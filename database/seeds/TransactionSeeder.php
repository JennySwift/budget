<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;
use Carbon\Carbon;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Tag;

class TransactionSeeder extends Seeder {

	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Transaction::truncate();
		
		

		/**
		 * Objective:
		 */
		
		$this->insertUserOneTransactions();
		$this->insertUserTwoTransactions();

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

	private function insertUserTwoTransactions()
	{
		$total = -10;

		Transaction::create([
			'date' => Carbon::today()->format('Y-m-d'),
			'type' => 'income',
			'description' => '',
			'merchant' => '',
			'total' => 1000,
			'account_id' => 1,
			'reconciled' => 0,
			'allocated' => 0,
			'user_id' => 2
		]);

		Transaction::create([
			'date' => Carbon::today()->format('Y-m-d'),
			'type' => 'expense',
			'description' => '',
			'merchant' => '',
			'total' => $total,
			'account_id' => 1,
			'reconciled' => 0,
			'allocated' => 0,
			'user_id' => 2
		]);

		//Insert transactions with fixed budget

		$id = Transaction::insertGetId([
			'date' => '2014-01-01',
			'type' => 'expense',
			'description' => 'fixed before CSD',
			'merchant' => '',
			'total' => $total,
			'account_id' => 1,
			'reconciled' => 0,
			'allocated' => 0,
			'user_id' => 2
		]);

		$transaction = Transaction::find($id);
		$transaction->tags()->attach(8, ['user_id' => 2, 'calculated_allocation' => $total]);

		$id = Transaction::insertGetId([
			'date' => Carbon::today()->format('Y-m-d'),
			'type' => 'expense',
			'description' => 'fixed after CSD',
			'merchant' => '',
			'total' => $total,
			'account_id' => 1,
			'reconciled' => 0,
			'allocated' => 0,
			'user_id' => 2
		]);

		$transaction = Transaction::find($id);
		$transaction->tags()->attach(8, ['user_id' => 2, 'calculated_allocation' => $total]);

		//Insert transactions with flex budget

		$id = Transaction::insertGetId([
			'date' => '2014-01-01',
			'type' => 'expense',
			'description' => 'flex budget',
			'merchant' => '',
			'total' => $total,
			'account_id' => 1,
			'reconciled' => 0,
			'allocated' => 0,
			'user_id' => 2
		]);

		$transaction = Transaction::find($id);
		$transaction->tags()->attach(11, ['user_id' => 2, 'calculated_allocation' => $total]);
	}

	private function insertUserOneTransactions()
	{
		$faker = Faker::create();
		
		$account_ids = Account::where('user_id', 1)->lists('id');
		
		foreach (range(1, 10) as $index) {
			$is_transfer = $faker->boolean($chanceOfGettingTrue = 20);
			$total = $faker->randomElement([5, 10, 15, 20]);

			if ($is_transfer) {
				$from_account_id = $faker->randomElement($account_ids);
				$to_account_id = $faker->randomElement($account_ids);

				while ($from_account_id === $to_account_id) {
					$to_account_id = $faker->randomElement($account_ids);
				}

				$date = $faker->date($format = 'Y-m-d', $max = 'now');
				// $description = $faker->word();
				$description = 'transfer';
				// $total = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 200);
				
				$negative_total = $total * -1;
				$reconciled = $faker->numberBetween($min = 0, $max = 1);

				Transaction::create([
					'date' => $date,
					'type' => 'transfer',
					'description' => $description,
					'total' => $total,
					'account_id' => $to_account_id,
					'reconciled' => $reconciled,
					'allocated' => 0,
					'user_id' => 1
				]);

				Transaction::create([
					'date' => $date,
					'type' => 'transfer',
					'description' => $description,
					'total' => $negative_total,
					'account_id' => $from_account_id,
					'reconciled' => $reconciled,
					'allocated' => 0,
					'user_id' => 1
				]);
			}

			else {
				$account_id = $faker->randomElement($account_ids);
				$type = $faker->randomElement(['income', 'expense']);

				if ($type === 'expense') {
					$total = $total * -1;
				}

				Transaction::create([
					'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
					'type' => $type,
					'description' => $faker->word(),
					'merchant' => $faker->name(),
					'total' => $total,
					'account_id' => $account_id,
					'reconciled' => $faker->numberBetween($min = 0, $max = 1),
					'allocated' => 0,
					'user_id' => 1
				]);
			}
			
		}
	}

}