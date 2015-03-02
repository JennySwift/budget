<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Color;
use App\Budget;
use App\Account;
use App\Transaction;
use App\Tag;
use App\Transaction_Tag;
use Faker\Factory as Faker;

// DB::enableQueryLog();

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		Model::unguard();

		// $this->call('UserTableSeeder');
		// $this->command->info('User table seeded!');

		Color::truncate();
		$this->call('ColorTableSeeder');
		$this->command->info('Color table seeded!');

		// Budget::truncate();
		$this->call('BudgetTableSeeder');
		$this->command->info('Budget table seeded!');

		Account::truncate();	
		$this->call('AccountTableSeeder');
		$this->command->info('Account table seeded!');

		Transaction::truncate();
		$this->call('TransactionTableSeeder');
		$this->command->info('Transaction table seeded!');

		Tag::truncate();
		$this->call('TagTableSeeder');
		$this->command->info('Tag table seeded!');

		Transaction_Tag::truncate();
		$this->call('TransactionTagTableSeeder');
		$this->command->info('transactions_tags table seeded!');

		DB::statement('SET FOREIGN_KEY_CHECKS=1');

	}

}

// ===============================Users Table===============================

class UserTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach (range(1, 5) as $index) {
			User::create([
				'name' => $faker->word,
				'email' => $faker->email,
				'password' => 'secret'
			]);
		}
	}

}

// ===============================Colors Table===============================

class ColorTableSeeder extends Seeder {

	public function run()
	{
		Color::create([
			'item' => 'income',
			'color' => '#017d00',
			'user_id' => 1
		]);
		Color::create([
			'item' => 'expense',
			'color' => '#fb5e52',
			'user_id' => 1
		]);
		Color::create([
			'item' => 'transfer',
			'color' => '#fca700',
			'user_id' => 1
		]);	
	}

}

// ===============================Budgets Table===============================

class BudgetTableSeeder extends Seeder {

	public function run()
	{
		Budget::create([
			'type' => 'fixed',
		]);
		Budget::create([
			'type' => 'flex',
		]);
		
	}

}

// ===============================Accounts Table===============================

class AccountTableSeeder extends Seeder {

	public function run()
	{
		Account::create([
			'name' => 'Cash',
			'user_id' => 1
		]);
		Account::create([
			'name' => 'iSaver',
			'user_id' => 1
		]);
		Account::create([
			'name' => 'Classic',
			'user_id' => 1
		]);
		Account::create([
			'name' => 'Bankwest Business',
			'user_id' => 1
		]);
		Account::create([
			'name' => 'Beach Mission Shower Money',
			'user_id' => 1
		]);
	}

}

// ===============================Transactions Table===============================

class TransactionTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();
		$account_ids = Account::where('user_id', 1)->lists('id');
		
		foreach (range(1, 50) as $index) {
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
					'account' => $to_account_id,
					'reconciled' => $reconciled,
					'allocated' => 0,
					'user_id' => 1
				]);

				Transaction::create([
					'date' => $date,
					'type' => 'transfer',
					'description' => $description,
					'total' => $negative_total,
					'account' => $from_account_id,
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
					'account' => $account_id,
					'reconciled' => $faker->numberBetween($min = 0, $max = 1),
					'allocated' => 0,
					'user_id' => 1
				]);
			}
			
		}
	}

}

// ===============================Tags Table===============================

class TagTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach (range(1, 15) as $index) {
			$has_budget = $faker->boolean($chanceOfGettingTrue = 50);

			if ($has_budget) {
				$budget_type = $faker->randomElement(['fixed_budget', 'flex_budget']);

				if ($budget_type === 'fixed_budget') {
					$budget = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 200);
					$budget_id = 1;
				}
				elseif ($budget_type === 'flex_budget') {
					$budget = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100);
					$budget_id = 2;
				}
				$starting_date = $faker->date($format = 'Y-m-d', $max = 'now');

				Log::info('budget_id: ' . $budget_id);

				Tag::create([
					'name' => $faker->word(),
					$budget_type => $budget,
					'starting_date' => $starting_date,
					'budget_id' => $budget_id,
					'user_id' => 1
				]);

				// $queries = DB::getQueryLog();
				// Log::info('queries', $queries);
			}

			else {
				//no budget for this tag
				Tag::create([
					'name' => $faker->word(),
					'user_id' => 1
				]);
			}
		}
	}

}

// ===============================transaction_tags table===============================

class TransactionTagTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();
		$transaction_ids = Transaction::where('type', '!=', 'transfer')->lists('id');
		$tag_ids = Tag::lists('id');

		foreach (range(1, 100) as $index) {
			$transaction_id = $faker->randomElement($transaction_ids);

			Transaction_Tag::create([
				'transaction_id' => $transaction_id,
				'tag_id' => $faker->randomElement($tag_ids),
				'user_id' => 1
			]);
		}

		foreach ($transaction_ids as $transaction_id) {
			$tag_ids = DB::table('transactions_tags')->where('transaction_id', $transaction_id)->lists('tag_id');

			$count = count($tag_ids);

			//we have the tags for the transaction	
			if ($count > 1) {
				//the transaction has at least two tags. lets see how many have budgets.
				$budget_counter = 0;
				foreach ($tag_ids as $tag_id) {
					$fixed_budget = DB::table('tags')->where('id', $tag_id)->pluck('fixed_budget');
					$flex_budget = DB::table('tags')->where('id', $tag_id)->pluck('flex_budget');
					if ($fixed_budget || $flex_budget) {
						$budget_counter++;
					}
				}
				if ($budget_counter > 1) {
					//the transaction has multiple budgets. Let's decide whether or not to mark it as allocated.
					$make_allocated = $faker->boolean($chanceOfGettingTrue = 50);

					if ($make_allocated) {
						//Now let's give each tag of the transaction an allocated budget, if the tag has a budget.
						foreach ($tag_ids as $tag_id) {
							$fixed_budget = DB::table('tags')->where('id', $tag_id)->pluck('fixed_budget');
							$flex_budget = DB::table('tags')->where('id', $tag_id)->pluck('flex_budget');
							
							if ($fixed_budget || $flex_budget) {
								$allocated_type = $faker->randomElement(['allocated_fixed', 'allocated_percent']);
								if ($allocated_type === 'allocated_fixed') {
									$transaction_total = DB::table('transactions')->where('id', $transaction_id)->pluck('total');

									$allocation = $faker->randomFloat(2, 0, $transaction_total);
								}
								elseif ($allocated_type === 'allocated_percent') {
									$allocation = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100);
								}

								$row = Transaction_Tag::where('transaction_id', $transaction_id)->where('tag_id', $tag_id)->first();
								if ($allocated_type === 'allocated_fixed') {
									$row->allocated_fixed = $allocation;
								}
								else {
									$row->allocated_percent = $allocation;
								}
								$row->calculated_allocation = 10;
								$row->save();
								
							}
						}
						//now mark the transaction as allocated
						$transaction = Transaction::where('id', $transaction_id)->first();
						$transaction->allocated = 1;
						$transaction->save();
					}
				}
			}
		}
		

		//get all transactions with their tags
		//find all the transactions that have multiple budgets
		//select a percentage of those transactions
		//loop through the selection and give the tags for each transaction an allocated budget
		//mark the transaction as allocated
	}

}
