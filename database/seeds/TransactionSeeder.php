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
            // Create transactions without budgets
            $this->createIncomeTransactionsWithoutBudgets($user, 2);
            $this->createExpenseTransactionsWithOutBudgets($user, 1);
            $this->createTransferTransactions($user, 1);

            //Create transactions with budgets
            $this->createExpenseTransactionsWithBudgets($user, 2);
            $this->createIncomeTransactionsWithBudgets($user, 2);
        }
	}

    private function createExpenseTransactionsWithBudgets($user, $num)
    {
        $dateBeforeStartingDate = $this->faker->dateTimeBetween('-2 years', '-1 years')->format('Y-m-d');
        $dateAfterStartingDate = $this->faker->dateTimeBetween('-1 months', 'now')->format('Y-m-d');

        foreach (range(1, $num) as $index) {
            //Create fixed budget expenses before starting date
            $transaction = $this->createExpense($user, $dateBeforeStartingDate);
            $this->addBudgetsToTransaction($transaction, $this->getRandomFixedBudgetIds($user));

            //Create fixed budget expenses after starting date
            $transaction = $this->createExpense($user, $dateAfterStartingDate);
            $this->addBudgetsToTransaction($transaction, $this->getRandomFixedBudgetIds($user));

            //Create flex budget expenses before starting date
            $transaction = $this->createExpense($user, $dateBeforeStartingDate);
            $this->addBudgetsToTransaction($transaction, $this->getRandomFlexBudgetIds($user));

            //Create flex budget expenses after starting date
            $transaction = $this->createExpense($user, $dateAfterStartingDate);
            $this->addBudgetsToTransaction($transaction, $this->getRandomFlexBudgetIds($user));

            //Create expenses before starting date with both fixed and flex budgets
            $transaction = $this->createExpense($user, $dateBeforeStartingDate);
            $this->addBudgetsToTransaction($transaction, $this->getRandomFixedAndFlexBudgetIds($user));

            //Create expenses after starting date with both fixed and flex budgets
            $transaction = $this->createExpense($user, $dateAfterStartingDate);
            $this->addBudgetsToTransaction($transaction, $this->getRandomFixedAndFlexBudgetIds($user));
        }
    }

    private function createIncomeTransactionsWithBudgets($user, $num)
    {
        $dateBeforeStartingDate = $this->faker->dateTimeBetween('-2 years', '-1 years')->format('Y-m-d');
        $dateAfterStartingDate = $this->faker->dateTimeBetween('-1 months', 'now')->format('Y-m-d');

        $businessId = Budget::where('user_id', $user->id)->whereName('business')->pluck('id');
        $buskingId = Budget::where('user_id', $user->id)->whereName('busking')->pluck('id');

        foreach (range(1, $num) as $index) {
            //Create fixed budget income before starting date for 'business' budget
            $transaction = $this->createIncome($user, $dateBeforeStartingDate);
            $this->addBudgetToTransaction($transaction, $businessId);

            //Create fixed budget income after starting date for 'business' budget
            $transaction = $this->createIncome($user, $dateAfterStartingDate);
            $this->addBudgetToTransaction($transaction, $businessId);

            //Create flex budget income before starting date for 'busking' budget
            $transaction = $this->createIncome($user, $dateBeforeStartingDate);
            $this->addBudgetToTransaction($transaction, $buskingId);

            //Create flex budget income after starting date for 'busking' budget
            $transaction = $this->createIncome($user, $dateAfterStartingDate);
            $this->addBudgetToTransaction($transaction, $buskingId);
        }
    }

    /**
     *
     * @param $transaction
     * @param $budgetIds
     * @internal param $user
     */
    private function addBudgetsToTransaction($transaction, $budgetIds)
    {
        foreach ($budgetIds as $budgetId) {
            $transaction = $this->addBudgetToTransaction($transaction, $budgetId);
        }

        $this->doAllocation($transaction);
    }

    /**
     *
     * @param $transaction
     * @param $budgetId
     */
    private function addBudgetToTransaction($transaction, $budgetId)
    {
        $transaction->budgets()->attach($budgetId, [
            'calculated_allocation' => $transaction->total
        ]);

        return $transaction;
    }

    /**
     * Get a least one fixed budget id and one flex budget id
     * @param $user
     * @return array
     */
    private function getRandomFixedAndFlexBudgetIds($user)
    {
        $fixedBudgetIds = Budget::where('user_id', $user->id)
            ->where('type', 'fixed')
            ->lists('id');

        $fixedBudgetIds = $this->faker->randomElements($fixedBudgetIds, $this->faker->numberBetween(1,2));

        $flexBudgetIds = Budget::where('user_id', $user->id)
            ->where('type', 'flex')
            ->lists('id');

        $flexBudgetIds = $this->faker->randomElements($flexBudgetIds, $this->faker->numberBetween(1,2));

        $fixedAndFlexBudgetIds = array_merge($fixedBudgetIds, $flexBudgetIds);

        return $fixedAndFlexBudgetIds;
    }

    private function getRandomFixedBudgetIds($user)
    {
        $budgetIds = Budget::where('user_id', $user->id)
            ->whereType('fixed')
            ->lists('id');

        return $this->faker->randomElements($budgetIds, $this->faker->numberBetween(1,3));
    }

    private function getRandomFlexBudgetIds($user)
    {
        $budgetIds = Budget::where('user_id', $user->id)
            ->whereType('flex')
            ->lists('id');

        return $this->faker->randomElements($budgetIds, $this->faker->numberBetween(1,3));
    }

    private function createIncomeTransactionsWithoutBudgets($user, $num)
    {
        foreach (range(1, $num) as $index) {
            $transaction = $this->createIncome($user, $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'));
        }
    }

    private function createTransferTransactions($user, $num)
    {
        foreach (range(1, $num) as $index) {
            $transaction = $this->createTransfer($user);
        }
    }

    private function createExpenseTransactionsWithoutBudgets($user, $num)
    {
        foreach (range(1, $num) as $index) {
            $transaction = $this->createExpense($user, $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'));
        }
    }

    /**
     *
     * @param $user
     * @param $addBudgets
     * @param $date
     */
    private function createExpense($user, $date)
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

        return $transaction;
    }

    /**
     *
     * @param $user
     */
    private function createIncome($user, $date)
    {
        $transaction = new Transaction([
            'type' => 'income',
            'date' => $date,
            'account_id' => Account::whereUserId($user->id)->get()->random(1)->id,
            'description' => $this->faker->sentence(1),
            'merchant' => $this->faker->name(),
            'total' => $this->faker->randomElement([100, 500, 1000, 2000]),
            'reconciled' => $this->faker->numberBetween($min = 0, $max = 1),
            'allocated' => 0,
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s')
        ]);
        $transaction->user()->associate($user);
        $transaction->save();

        return $transaction;
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