<?php

use App\User;
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

        if (app()->env === 'local') {
            Transaction::truncate();
            DB::table('transactions_tags')->truncate();

            $this->insertTransactions(1);
        }
        else {
            $this->insertTransactions(User::whereEmail('cheezyspaghetti@optusnet.com.au')->id);
        }

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

	private function insertTransactions($user_id)
	{
		$faker = Faker::create();
		
		$account_ids = Account::where('user_id', $user_id)->lists('id');
        $tag_ids = Tag::where('user_id', $user_id)->lists('id');
		
		foreach (range(1, 10) as $index) {
			$is_transfer = $faker->boolean($chanceOfGettingTrue = 20);
			$total = $faker->randomElement([5, 10, 15, 20]);

			if ($is_transfer) {
                $this->insertTransfer($total, $account_ids, $user_id);
			}

			else {
				$account_id = $faker->randomElement($account_ids);
				$type = $faker->randomElement(['income', 'expense']);

				if ($type === 'expense') {
					$total = $total * -1;
				}

				$transaction = $this->insertTransaction($type, $total, $account_id, $user_id);

//                $number = $faker->numberBetween(1,3);
                $number = 1;
                $random_tag_ids = $faker->randomElements($tag_ids, $number);

                foreach ($random_tag_ids as $tag_id) {
                    $transaction->tags()->attach($tag_id, [
                        'calculated_allocation' => $transaction->total
                    ]);
                }

                $this->doAllocation($transaction);

			}
			
		}

        //So that balance is likely to be in the positive
        $this->insertTransaction('income', 1000, 1, $user_id);
	}

    private function doAllocation($transaction)
    {
        $counter = 0;
        //Count the number of tags the transaction has that have a budget
        foreach ($transaction->tags as $tag) {
            if ($tag->fixed_budget || $tag->flex_budget) {
                $counter++;
            }
        }
        if ($counter > 1) {
            //Transaction has multiple tags with budgets
            $counter = 0;
            foreach ($transaction->tags as $tag) {
                $counter++;
                //Set the allocation of the first tag to 100% and the rest 0%
                if ($counter === 1) {
                    $transaction->tags()->updateExistingPivot($tag->id, [
                        'allocated_percent' => 100
                    ]);
                }
                else {
                    $transaction->tags()->updateExistingPivot($tag->id, [
                        'allocated_percent' => 0,
                        'calculated_allocation' => 0
                    ]);
                }
//                $tag->save();
            }

            $transaction->allocated = 1;
            $transaction->save();
        }
    }

    private function insertTransfer($total, $account_ids, $user_id)
    {
        $faker = Faker::create();
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
            'user_id' => $user_id
        ]);

        Transaction::create([
            'date' => $date,
            'type' => 'transfer',
            'description' => $description,
            'total' => $negative_total,
            'account_id' => $from_account_id,
            'reconciled' => $reconciled,
            'allocated' => 0,
            'user_id' => $user_id
        ]);
    }

    private function insertTransaction($type, $total, $account_id, $user_id)
    {
        $faker = Faker::create();
        $transaction = Transaction::create([
            'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
            'type' => $type,
            'description' => $faker->sentence(),
            'merchant' => $faker->name(),
            'total' => $total,
            'account_id' => $account_id,
            'reconciled' => $faker->numberBetween($min = 0, $max = 1),
            'allocated' => 0,
            'user_id' => $user_id
        ]);

        return $transaction;
    }

}