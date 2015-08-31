<?php

use App\Models\Budget;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Tag;

/**
 * Class TransactionSeeder
 */
class TransactionSeeder extends Seeder {

    /**
     * @var int
     */
    protected $howManyTransactions = 3;

    /**
     *
     */
    public function run()
	{
        $this->faker = Faker::create();
		$users = User::all();

        foreach($users as $user) {
            $this->createTransactionsForUser($user);
        }
	}

    /**
     *
     * @param $user
     */
    private function createTransactionsForUser($user)
    {
        foreach (range(0, $this->howManyTransactions) as $index) {
            $this->createIncome($user, $this->faker->randomElement([20, 50, 100]));
            $dateBeforeStartingDate = $this->faker->dateTimeBetween('-2 years', '-1 years')->format('Y-m-d');
            $dateAfterStartingDate = $this->faker->dateTimeBetween('-1 months', 'now')->format('Y-m-d');

            if ($index === 0) {
                //Create an expense transaction with no budgets
                $this->createExpense($user, false, $dateAfterStartingDate);
            }
            else {
                if ($index === 1) {
                    //Create an expense transaction with budgets before the starting date
                    $this->createExpense($user, true, $dateBeforeStartingDate);
                }
                else {
                    if ($index >= 2) {
                        //Create an expense transaction with budgets after the starting date
                        $this->createExpense($user, true, $dateAfterStartingDate);
                    }
                }
            }
            //Todo: make it foolproof so that there is at least one transaction
            // with a fixed budget before starting date, one with fixed after
            // starting date, and same for flex. Currently budget type is random.
            // I'll need to do the allocation right for it too.
        }
        //Give the user just one transfer transaction
        $this->createTransfer($user);

        //Give the user a big income transaction so their remaining balance is positive
        $this->createIncome($user, 5000);
    }

    /**
     *
     * @param $user
     * @param $addBudgets
     * @param $date
     */
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

    /**
     *
     * @param $user
     * @param $transaction
     */
    private function addBudgetsToTransaction($user, $transaction)
    {
        $randomBudgetIds = $this->faker->randomElements(Budget::where('user_id', $user->id)->lists('id'), $this->faker->numberBetween(1,3));

        foreach ($randomBudgetIds as $budgetId) {
            $transaction->budgets()->attach($budgetId, [
                'calculated_allocation' => $transaction->total
            ]);
        }

        $this->doAllocation($transaction);
    }

    /**
     *
     * @param $user
     */
    private function createIncome($user, $total)
    {
        $transaction = new Transaction([
            'type' => 'income',
            'date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'account_id' => Account::whereUserId($user->id)->get()->random(1)->id,
            'description' => $this->faker->sentence(1),
            'merchant' => $this->faker->name(),
            'total' => $total,
            'reconciled' => $this->faker->numberBetween($min = 0, $max = 1),
            'allocated' => 0,
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s')
        ]);
        $transaction->user()->associate($user);
        $transaction->save();
        $this->addBudgetsToTransaction($user, $transaction);
    }

    /**
     *
     * @param $user
     */
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

    /**
     *
     * @param $transaction
     */
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
            }

            $transaction->allocated = 1;
            $transaction->save();
        }
    }
}