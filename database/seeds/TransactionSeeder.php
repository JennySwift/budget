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
 * @property  fixedBudgetIds
 * @property  flexBudgetIds
 * @property  mixedBudgetIds
 */
class TransactionSeeder extends Seeder {

    /**
     * @var int
     */
    protected $howManyTransactions = 3;
    /**
     * Fixed budgets ids that belong to the user
     * @var
     */
    protected $fixedBudgetIds;
    /**
     * Flex budget ids that belong to the user
     * @var
     */
    protected $flexBudgetIds;
    /**
     * One fixed budget id and one flex budget id that belong to the user
     * @var
     */
    protected $mixedBudgetIds;

    /**
     *
     */
    public function run()
	{
        $this->faker = Faker::create();
		$users = User::all();

        foreach($users as $user) {
            // Get budget ids for user
            $this->fixedBudgetIds = $user->fixedBudgets()->lists('id')->all();
            $this->flexBudgetIds = $user->flexBudgets()->lists('id')->all();
            $this->mixedBudgetIds = [$this->fixedBudgetIds[0], $this->flexBudgetIds[0]];

            // Create transactions without budgets
            $this->createIncomeTransactionsWithoutBudgets($user, 1);
            $this->createExpenseTransactionsWithOutBudgets($user, 1);
            $this->createTransferTransactions($user, 1);

            //Create transactions with budgets
            $this->createExpenseTransactionsWithBudgets($user, 1);
            $this->createIncomeTransactionsWithBudgets($user, 1);
        }
	}

    /**
     *
     * @param $user
     * @param $num
     */
    private function createExpenseTransactionsWithBudgets($user, $num)
    {
        $dateBeforeStartingDate = $this->faker->dateTimeBetween('-2 years', '-1 years')->format('Y-m-d');
        $dateAfterStartingDate = $this->faker->dateTimeBetween('-1 months', 'now')->format('Y-m-d');
        $bankFeesId = Budget::where('user_id', $user->id)->whereName('bank fees')->pluck('id');
        foreach (range(1, $num) as $index) {
            //Create fixed budget expenses before starting date
            $transaction = $this->createExpense($user, $dateBeforeStartingDate, 5);
//            $this->addBudgetsToTransaction($transaction, $this->getRandomFixedBudgetIds($user));
            $this->addBudgetsToTransaction($transaction, $this->fixedBudgetIds);

            //Create fixed budget expenses after starting date
            $transaction = $this->createExpense($user, $dateAfterStartingDate, 10);
            $this->addBudgetsToTransaction($transaction, $this->fixedBudgetIds);

            //Create flex budget expenses before starting date
            $transaction = $this->createExpense($user, $dateBeforeStartingDate, 15);
            $this->addBudgetsToTransaction($transaction, $this->flexBudgetIds);

            //Create flex budget expenses after starting date
            $transaction = $this->createExpense($user, $dateAfterStartingDate, 20);
            $this->addBudgetsToTransaction($transaction, $this->flexBudgetIds);

            //Create expenses before starting date with both fixed and flex budgets
            $transaction = $this->createExpense($user, $dateBeforeStartingDate, 25);
            $this->addBudgetsToTransaction($transaction, $this->mixedBudgetIds);

            //Create expenses after starting date with both fixed and flex budgets
            $transaction = $this->createExpense($user, $dateAfterStartingDate, 30);
            $this->addBudgetsToTransaction($transaction, $this->mixedBudgetIds);

            //Create expenses with unassigned budget
            $transaction = $this->createExpense($user, $dateAfterStartingDate, 5);
            $this->addBudgetToTransaction($transaction, $bankFeesId);
        }
    }

    /**
     *
     * @param $user
     * @param $num
     */
    private function createIncomeTransactionsWithBudgets($user, $num)
    {
        $dateBeforeStartingDate = $this->faker->dateTimeBetween('-2 years', '-1 years')->format('Y-m-d');
        $dateAfterStartingDate = $this->faker->dateTimeBetween('-1 months', 'now')->format('Y-m-d');

        $businessId = Budget::where('user_id', $user->id)->whereName('business')->pluck('id');
        $buskingId = Budget::where('user_id', $user->id)->whereName('busking')->pluck('id');
        $somethingId = Budget::where('user_id', $user->id)->whereName('something')->pluck('id');

        foreach (range(1, $num) as $index) {
            //Create fixed budget income before starting date for 'business' budget
            $transaction = $this->createIncome($user, $dateBeforeStartingDate, 100);
            $this->addBudgetToTransaction($transaction, $businessId);

            //Create fixed budget income after starting date for 'business' budget
            $transaction = $this->createIncome($user, $dateAfterStartingDate, 200);
            $this->addBudgetToTransaction($transaction, $businessId);

            //Create flex budget income before starting date for 'busking' budget
            $transaction = $this->createIncome($user, $dateBeforeStartingDate, 500);
            $this->addBudgetToTransaction($transaction, $buskingId);

            //Create flex budget income after starting date for 'busking' budget
            $transaction = $this->createIncome($user, $dateAfterStartingDate, 1000);
            $this->addBudgetToTransaction($transaction, $buskingId);

            //Create income with unassigned budget
            $transaction = $this->createIncome($user, $dateAfterStartingDate, 250);
            $this->addBudgetToTransaction($transaction, $somethingId);
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
            ->lists('id')->all();

        $fixedBudgetIds = $this->faker->randomElements($fixedBudgetIds, $this->faker->numberBetween(1,2));

        $flexBudgetIds = Budget::where('user_id', $user->id)
            ->where('type', 'flex')
            ->lists('id')->all();

        $flexBudgetIds = $this->faker->randomElements($flexBudgetIds, $this->faker->numberBetween(1,2));

        $fixedAndFlexBudgetIds = array_merge($fixedBudgetIds, $flexBudgetIds);

        return $fixedAndFlexBudgetIds;
    }

    /**
     *
     * @param $user
     * @return mixed
     */
    private function getRandomFixedBudgetIds($user)
    {
        $budgetIds = Budget::where('user_id', $user->id)
            ->whereType('fixed')
            ->lists('id')->all();

        return $this->faker->randomElements($budgetIds, $this->faker->numberBetween(1,3));
    }

    /**
     *
     * @param $user
     * @return mixed
     */
    private function getRandomFlexBudgetIds($user)
    {
        $budgetIds = Budget::where('user_id', $user->id)
            ->whereType('flex')
            ->lists('id')->all();

        return $this->faker->randomElements($budgetIds, $this->faker->numberBetween(1,3));
    }

    /**
     *
     * @param $user
     * @return mixed
     */
    private function getRandomUnassignedBudgetIds($user)
    {
        $budgetIds = Budget::where('user_id', $user->id)
            ->whereType('unassigned')
            ->lists('id')->all();

        return $this->faker->randomElements($budgetIds, $this->faker->numberBetween(1,3));
    }

    /**
     *
     * @param $user
     * @param $num
     */
    private function createIncomeTransactionsWithoutBudgets($user, $num)
    {
        foreach (range(1, $num) as $index) {
            $transaction = $this->createIncome($user, $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'), 300);
        }
    }

    /**
     *
     * @param $user
     * @param $num
     */
    private function createTransferTransactions($user, $num)
    {
        foreach (range(1, $num) as $index) {
            $transaction = $this->createTransfer($user);
        }
    }

    /**
     *
     * @param $user
     * @param $num
     */
    private function createExpenseTransactionsWithoutBudgets($user, $num)
    {
        foreach (range(1, $num) as $index) {
            $transaction = $this->createExpense($user, $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'), 50);
        }
    }

    /**
     *
     * @param $user
     * @param $addBudgets
     * @param $date
     */
    private function createExpense($user, $date, $total)
    {
        $transaction = new Transaction([
            'type' => 'expense',
            'date' => $date,
            'account_id' => Account::whereUserId($user->id)->get()->random(1)->id,
            'description' => $this->faker->sentence(1),
            'merchant' => $this->faker->name(),
//            'total' => $this->faker->randomElement([5, 10, 15, 20]) * -1,
            'total' => $total * -1,
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
    private function createIncome($user, $date, $total)
    {
        $transaction = new Transaction([
            'type' => 'income',
            'date' => $date,
            'account_id' => Account::whereUserId($user->id)->get()->random(1)->id,
            'description' => $this->faker->sentence(1),
            'merchant' => $this->faker->name(),
//            'total' => $this->faker->randomElement([100, 500, 1000, 2000]),
            'total' => $total,
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
//        $total = $this->faker->randomElement([5, 10, 15, 20]);
        $total = 100;
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
            'total' => $total * -1,
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
            'total' => $total,
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