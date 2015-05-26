<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;

use App\Models\Transaction_Tag;
use App\Models\Transaction;
use App\Models\Tag;

class TransactionTagSeeder extends Seeder {

	public function run()
	{
		Transaction_Tag::truncate();
		
		$faker = Faker::create();

		/**
		 * Objective:
		 */
		
		$transaction_ids = Transaction::where('type', '!=', 'transfer')->lists('id');
		$tag_ids = Tag::lists('id');

		//get all transactions with their tags
		//find all the transactions that have multiple budgets
		//select a percentage of those transactions
		//loop through the selection and give the tags for each transaction an allocated budget
		//mark the transaction as allocated

		foreach (range(1, 20) as $index) {
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
	}

}