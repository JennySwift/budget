<?php

use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Tag;

class TransactionSeeder extends Seeder {

    protected $howManyTransactions = 5;

	public function run()
	{
        $this->faker = Faker::create();
		$users = User::all();

        foreach($users as $user) {
            foreach(range(0, $this->howManyTransactions) as $index) {
                $this->createIncome($user);
                $this->createExpense($user);
                $this->createTransfer($user);
            }
        }
	}

    private function createIncome($user)
    {
        $transaction = new Transaction([
            'type' => 'income',
            'date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'account_id' => Account::whereUserId($user->id)->get()->random(1)->id,
            'description' => $this->faker->sentence(1),
            'merchant' => $this->faker->name(),
            'total' => $this->faker->randomElement([5, 10, 15, 20]),
            'reconciled' => $this->faker->numberBetween($min = 0, $max = 1),
            'allocated' => 0,
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s')
        ]);
        $transaction->user()->associate($user);
        $transaction->save();
    }

    private function createExpense($user)
    {
        $transaction = new Transaction([
            'type' => 'income',
            'date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'account_id' => Account::whereUserId($user->id)->get()->random(1)->id,
            'description' => $this->faker->sentence(1),
            'merchant' => $this->faker->name(),
            'total' => $this->faker->randomElement([5, 10, 15, 20]),
            'reconciled' => $this->faker->numberBetween($min = 0, $max = 1),
            'allocated' => 0,
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s')
        ]);
        $transaction->user()->associate($user);
        $transaction->save();
    }

    private function createTransfer($user)
    {
        $from_account = Account::whereUserId($user->id)->get()->random(1);
        $to_account = Account::whereUserId($user->id)->get()->random(1);

        $date = $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s');
        $amount = $this->faker->randomElement([5, 10, 15, 20]);
        $description = $this->faker->sentence(1);

        while ($from_account->id == $to_account->id) {
            $to_account = Account::whereUserId($user->id)->get()->random(1);
        }

        $transaction = new Transaction([
            'type' => 'transfer',
            'date' => $date,
            'account_id' => $from_account->id,
            'description' => $description,
            'merchant' => NULL,
            'total' => $amount,
            'reconciled' => $this->faker->numberBetween($min = 0, $max = 1),
            'allocated' => 0,
            'created_at' => $date
        ]);
        $transaction->user()->associate($user);
        $transaction->save();

        $transaction = new Transaction([
            'type' => 'transfer',
            'date' => $date,
            'account_id' => $to_account->id,
            'description' => $description,
            'merchant' => NULL,
            'total' => $amount,
            'reconciled' => $this->faker->numberBetween($min = 0, $max = 1),
            'allocated' => 0,
            'created_at' => $date
        ]);
        $transaction->user()->associate($user);
        $transaction->save();
    }

//    private function doAllocation($transaction)
//    {
//        $counter = 0;
//        //Count the number of tags the transaction has that have a budget
//        foreach ($transaction->tags as $tag) {
//            if ($tag->fixed_budget || $tag->flex_budget) {
//                $counter++;
//            }
//        }
//        if ($counter > 1) {
//            //Transaction has multiple tags with budgets
//            $counter = 0;
//            foreach ($transaction->tags as $tag) {
//                $counter++;
//                //Set the allocation of the first tag to 100% and the rest 0%
//                if ($counter === 1) {
//                    $transaction->tags()->updateExistingPivot($tag->id, [
//                        'allocated_percent' => 100
//                    ]);
//                }
//                else {
//                    $transaction->tags()->updateExistingPivot($tag->id, [
//                        'allocated_percent' => 0,
//                        'calculated_allocation' => 0
//                    ]);
//                }
////                $tag->save();
//            }
//
//            $transaction->allocated = 1;
//            $transaction->save();
//        }
//    }

//	private function insertTransactions($user)
//	{
//		$faker = Faker::create();
//
//		$account_ids = Account::where('user_id', $user->id)->lists('id');
//        $tag_ids = Tag::where('user_id', $user->id)->lists('id');
//
//        if (app()->env === 'local') {
//            $num_transactions = 15;
//        }
//        else {
//            $num_transactions = 100;
//        }
//
//		foreach (range(1, $num_transactions) as $index) {
//			$is_transfer = $faker->boolean($chanceOfGettingTrue = 20);
//			$total = $faker->randomElement([5, 10, 15, 20]);
//
//			if ($is_transfer) {
//                $this->insertTransfer($total, $account_ids, $user);
//			}
//
//			else {
//				$account_id = $faker->randomElement($account_ids);
//				$type = $faker->randomElement(['income', 'expense']);
//
//				if ($type === 'expense') {
//					$total = $total * -1;
//				}
//
//				$transaction = $this->insertTransaction($type, $total, $account_id, $user);
//
//                $number = $faker->numberBetween(1,3);
////                $number = 1;
//                $random_tag_ids = $faker->randomElements($tag_ids, $number);
//
//                foreach ($random_tag_ids as $tag_id) {
//                    $transaction->tags()->attach($tag_id, [
//                        'calculated_allocation' => $transaction->total
//                    ]);
//                }
//
//                $this->doAllocation($transaction);
//
//			}
//
//		}
//
//        //So that balance is likely to be in the positive
//        $this->insertTransaction('income', 1000, 1, $user);
//	}
//

//
//    private function insertTransfer($total, $account_ids, $user)
//    {
//        $faker = Faker::create();
//        $from_account_id = $faker->randomElement($account_ids);
//        $to_account_id = $faker->randomElement($account_ids);
//
//        while ($from_account_id === $to_account_id) {
//            $to_account_id = $faker->randomElement($account_ids);
//        }
//
////        $date = $faker->date($format = 'Y-m-d', $max = 'now');
//        $date = $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d');
//        // $description = $faker->word();
//        $description = 'transfer';
//        // $total = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 200);
//
//        $negative_total = $total * -1;
//        $reconciled = $faker->numberBetween($min = 0, $max = 1);
//
//        $transaction = new Transaction([
//            'date' => $date,
//            'type' => 'transfer',
//            'description' => $description,
//            'total' => $total,
//            'account_id' => $to_account_id,
//            'reconciled' => $reconciled,
//            'allocated' => 0
//        ]);
//
//        $transaction->user()->associate($user);
//        $transaction->save();
//
//        $transaction = new Transaction([
//            'date' => $date,
//            'type' => 'transfer',
//            'description' => $description,
//            'total' => $negative_total,
//            'account_id' => $from_account_id,
//            'reconciled' => $reconciled,
//            'allocated' => 0
//        ]);
//
//        $transaction->user()->associate($user);
//        $transaction->save();
//    }
//
//    private function insertTransaction($type, $total, $account_id, $user)
//    {
//        $faker = Faker::create();
//
//        $transaction = new Transaction([
//            'date' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
//            'type' => $type,
//            'description' => $faker->sentence(1),
//            'merchant' => $faker->name(),
//            'total' => $total,
//            'account_id' => $account_id,
//            'reconciled' => $faker->numberBetween($min = 0, $max = 1),
//            'allocated' => 0
//        ]);
//
//        $transaction->user()->associate($user);
//        $transaction->save();
//
//        return $transaction;
//    }

}