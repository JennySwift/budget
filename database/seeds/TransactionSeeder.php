<?php

use App\Models\Budget;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Tag;

class TransactionSeeder extends Seeder {

    protected $howManyTransactions = 3;

	public function run()
	{
        $this->faker = Faker::create();
		$users = User::all();

        foreach($users as $user) {
            foreach(range(0, $this->howManyTransactions) as $index) {
                $this->createIncome($user);
                $dateBeforeStartingDate = $this->faker->dateTimeBetween('-2 years', '-1 years')->format('Y-m-d');
                $dateAfterStartingDate = $this->faker->dateTimeBetween('-1 months', 'now')->format('Y-m-d');

                if ($index === 0) {
                    //Create an expense transaction with no budgets
                    $this->createExpense($user, false, $dateAfterStartingDate);
                }
                else if ($index === 1) {
                    //Create an expense transaction with budgets before the starting date
                    $this->createExpense($user, true, $dateBeforeStartingDate);
                }
                else if ($index >= 2) {
                    //Create an expense transaction with budgets after the starting date
                    $this->createExpense($user, true, $dateAfterStartingDate);
                }
                //Todo: make it foolproof so that there is at least one transaction
                // with a fixed budget before starting date, one with fixed after
                // starting date, and same for flex. Currently budget type is random.
            }
            //Give the user just one transfer transaction
            $this->createTransfer($user);
        }
	}

    private function addBudgetsToTransaction($user, $transaction)
    {
        $budgetIds = Budget::where('user_id', $user->id)->lists('id');
        $number = $this->faker->numberBetween(1,3);
        $randomBudgetIds = $this->faker->randomElements($budgetIds, $number);

        foreach ($randomBudgetIds as $budgetId) {
            $transaction->budgets()->attach($budgetId, [
                'calculated_allocation' => $transaction->total
            ]);
        }

        $this->doAllocation($transaction);
    }

    private function createIncome($user)
    {
        $transaction = new Transaction([
            'type' => 'income',
            'date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'account_id' => Account::whereUserId($user->id)->get()->random(1)->id,
            'description' => $this->faker->sentence(1),
            'merchant' => $this->faker->name(),
            'total' => $this->faker->randomElement([20, 50, 100]),
            'reconciled' => $this->faker->numberBetween($min = 0, $max = 1),
            'allocated' => 0,
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s')
        ]);
        $transaction->user()->associate($user);
        $transaction->save();
        $this->addBudgetsToTransaction($user, $transaction);
    }

    private function createExpense($user, $addBudgets, $date)
    {
        $transaction = new Transaction([
            'type' => 'expense',
            'date' => $date,
            'account_id' => Account::whereUserId($user->id)->get()->random(1)->id,
            'description' => $this->faker->sentence(1),
            'merchant' => $this->faker->name(),
            'total' => $this->faker->randomElement([5, 10, 15, 20]) * -1,
            'reconciled' => $this->faker->numberBetween($min = 0, $max = 1),
            'allocated' => 0,
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s')
        ]);
        $transaction->user()->associate($user);
        $transaction->save();

        if ($addBudgets) {
            $this->addBudgetsToTransaction($user, $transaction);
        }
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

    private function doAllocation($transaction)
    {
        if (count($transaction->budgets) > 1) {
            //Transaction has multiple budgets
            $counter = 0;
            foreach ($transaction->budgets as $budget) {
                $counter++;
                //Set the allocation of the first tag to 100% and the rest 0%
                if ($counter === 1) {
                    $transaction->budgets()->updateExistingPivot($budget->id, [
                        'allocated_percent' => 100
                    ]);
                }
                else {
                    $transaction->budgets()->updateExistingPivot($budget->id, [
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