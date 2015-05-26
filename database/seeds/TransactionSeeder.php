<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;

use App\Models\Transaction;
use App\Models\Account;

class TransactionSeeder extends Seeder {

	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Transaction::truncate();
		
		$faker = Faker::create();

		/**
		 * Objective:
		 */
		
		$account_ids = Account::where('user_id', 1)->lists('id');
		
		foreach (range(1, 10) as $index) {
			$is_transfer = $faker->boolean($chanceOfGettingTrue = 20);

			if ($is_transfer) {
				$from_account_id = $faker->randomElement($account_ids);
				$to_account_id = $faker->randomElement($account_ids);

				while ($from_account_id === $to_account_id) {
					$to_account_id = $faker->randomElement($account_ids);
				}

				$date = $faker->date($format = 'Y-m-d', $max = 'now');
				// $description = $faker->word();
				$description = 'transfer';
				$total = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 200);
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

				Transaction::create([
					'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
					'type' => $faker->randomElement(['income', 'expense']),
					'description' => $faker->word(),
					'merchant' => $faker->name(),
					'total' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 200),
					'account_id' => $account_id,
					'reconciled' => $faker->numberBetween($min = 0, $max = 1),
					'allocated' => 0,
					'user_id' => 1
				]);
			}
			
		}

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}