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
		
		$transaction_ids = Transaction::where('type', '!=', 'transfer')->lists('id');
		$tag_ids = Tag::lists('id');

		/**
		 * Objective:
		 * Loop through all transactions,
		 * adding a random number of tags to each transaction, with no duplicate tags.
		 * Currently, it is looping three times but not adding the tag if the transaction already has it.
		 */
		foreach ($transaction_ids as $transaction_id) {
			foreach (range(1, 3) as $index) {
				$tag_id = $faker->randomElement($tag_ids);
				$tags_for_transaction = Transaction::find($transaction_id)->tags;
				
				//Populate $tag_ids_for_transaction
				$tag_ids_for_transaction = [];
				foreach ($tags_for_transaction as $tag) {
					$tag_ids_for_transaction[] = $tag->id;
				}
				//Check the transaction doesn't already have the tag id.
				//If it doesn't, then add the tag to the transaction.
				if (!in_array($tag_id, $tag_ids_for_transaction)) {
					Transaction_Tag::create([
						'transaction_id' => $transaction_id,
						'tag_id' => $tag_id,
						'user_id' => 1
					]);
				}	
			}
		}

		/**
		 * This was working before but then started erroring after I seeded tags for user 2.
		 */

		/**
		 * Loop through the ids of income and expense transactions.
		 * Get the tag ids for each transaction.
		 * Count how many tags the transaction has.
		 * See how many budgets each of those tags has.
		 * If the transaction has multiple budgets, decide whether or not to mark the transaction as allocated.
		 */
		// foreach ($transaction_ids as $transaction_id) {
		// 	$tag_ids = DB::table('transactions_tags')->where('transaction_id', $transaction_id)->lists('tag_id');

		// 	$count = count($tag_ids);

		// 	//we have the tags for the transaction	
		// 	if ($count > 1) {
		// 		//the transaction has at least two tags. lets see how many have budgets.
		// 		$budget_counter = 0;
		// 		foreach ($tag_ids as $tag_id) {
		// 			$fixed_budget = DB::table('tags')->where('id', $tag_id)->pluck('fixed_budget');
		// 			$flex_budget = DB::table('tags')->where('id', $tag_id)->pluck('flex_budget');
		// 			if ($fixed_budget || $flex_budget) {
		// 				$budget_counter++;
		// 			}
		// 		}
		// 		if ($budget_counter > 1) {
		// 			//the transaction has multiple budgets. Let's decide whether or not to mark it as allocated.
		// 			$make_allocated = $faker->boolean($chanceOfGettingTrue = 50);

		// 			if ($make_allocated) {
		// 				//Now let's give each tag of the transaction an allocated budget, if the tag has a budget.
		// 				foreach ($tag_ids as $tag_id) {
		// 					$fixed_budget = DB::table('tags')->where('id', $tag_id)->pluck('fixed_budget');
		// 					$flex_budget = DB::table('tags')->where('id', $tag_id)->pluck('flex_budget');
							
		// 					if ($fixed_budget || $flex_budget) {
		// 						$allocated_type = $faker->randomElement(['allocated_fixed', 'allocated_percent']);

		// 						if ($allocated_type === 'allocated_fixed') {
		// 							$transaction_total = DB::table('transactions')->where('id', $transaction_id)->pluck('total');
		// 							$allocation = $faker->numberBetween(1, $transaction_total);
		// 							$calculated_allocation = $allocation;
		// 						}
		// 						elseif ($allocated_type === 'allocated_percent') {
		// 							$allocation = $faker->randomElement([10, 50, 100]);
		// 							$transaction = Transaction::find($transaction_id);
		// 							$total = $transaction->total;
		// 							$calculated_allocation = $total / 100 * $allocation;
		// 							// var_dump('transaction_id: ' . $transaction_id . '. total: ' . $total . '. allocation: ' . $allocation . '. calculated_allocation: ' . $calculated_allocation);
		// 						}

		// 						$row = Transaction_Tag::where('transaction_id', $transaction_id)->where('tag_id', $tag_id)->first();
								
		// 						if ($allocated_type === 'allocated_fixed') {
		// 							$row->allocated_fixed = $allocation;
		// 						}
		// 						else {
		// 							$row->allocated_percent = $allocation;
		// 						}
		// 						$row->calculated_allocation = $calculated_allocation;
		// 						$row->save();
								
		// 					}
		// 				}
		// 				//now mark the transaction as allocated
		// 				$transaction = Transaction::where('id', $transaction_id)->first();
		// 				$transaction->allocated = 1;
		// 				$transaction->save();
		// 			}
		// 		}
		// 	}
		// }
	}

}