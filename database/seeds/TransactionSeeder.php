<?php

use App\Models\Budget;
use App\User;
use Carbon\Carbon;
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

    protected $date;
    private $accounts;
    private $expenseMerchants;
    private $expenseMerchantsIndex;

    /**
     *
     */
    public function run()
	{
        $this->faker = Faker::create();
		$users = User::all();

        $this->expenseMerchants = [
            'John Doe',
            'Jane Doe',
            'John Smith',
            'Sally Woods',
            'Fred'
        ];

        $this->expenseMerchantsIndex = -1;

        foreach($users as $user) {
            $this->accounts = Account::where('user_id', $user->id)->get();

            // Get budget ids for user
            $this->fixedBudgetIds = $user->fixedBudgets()->pluck('id')->all();
            $this->flexBudgetIds = $user->flexBudgets()->pluck('id')->all();
            $this->mixedBudgetIds = [$this->fixedBudgetIds[0], $this->flexBudgetIds[0]];

            $num = 1;
            if ($user->id === 2) {
                $num = 20;
            }

            // Create transactions without budgets
            $this->createIncomeTransactionsWithoutBudgets($user, $num);
            $this->createExpenseTransactionsWithoutBudgets($user, $num);
            $this->createTransferTransactions($user, $num);

            //Create transactions with budgets
            $this->createExpenseTransactionsWithBudgets($user, $num);
            $this->createIncomeTransactionsWithBudgets($user, $num);

            $this->createDuplicateTransactions($user);
        }
	}

    /**
     *
     * @param $user
     */
    private function createDuplicateTransactions($user)
    {
        foreach (range(1,5) as $index) {
            $transaction = new Transaction([
                'type' => 'expense',
                'date' => Carbon::today()->subDays($index)->format('Y-m-d'),
                'account_id' => $user->accounts[0]->id,
                'description' => 'a duplicate description',
                'merchant' => 'a duplicate merchant',
                'total' => -55,
                'reconciled' => false,
                'allocated' => 0,
            ]);

            $transaction->user()->associate($user);
            $transaction->save();
        }
	}

    /**
     *
     * @param $user
     * @param $num
     */
    private function createExpenseTransactionsWithBudgets($user, $num)
    {
        $dateBeforeStartingDate = Config::get('budgets.dateBeforeStartingDateForExpenseTransactions');
        $dateAfterStartingDate = Config::get('budgets.dateAfterStartingDateForExpenseTransactions');
        $bankFeesId = Budget::where('user_id', $user->id)->whereName('bank fees')->value('id');

        foreach (range(1, $num) as $index) {
            //Create fixed budget expenses before starting date
            $transaction = $this->createExpense($user, $dateBeforeStartingDate, 5, 1, $this->accounts[0]);
            $this->addBudgetsToTransaction($transaction, $this->fixedBudgetIds);

            //Create fixed budget expenses after starting date
            $transaction = $this->createExpense($user, $dateAfterStartingDate, 10, 0, $this->accounts[0]);
            $this->addBudgetsToTransaction($transaction, $this->fixedBudgetIds);

            //Create flex budget expenses before starting date
            $transaction = $this->createExpense($user, $dateBeforeStartingDate, 15, 1, $this->accounts[0]);
            $this->addBudgetsToTransaction($transaction, $this->flexBudgetIds);

            //Create flex budget expenses after starting date
            $transaction = $this->createExpense($user, $dateAfterStartingDate, 20, 0, $this->accounts[0]);
            $this->addBudgetsToTransaction($transaction, $this->flexBudgetIds);

            //Create expenses before starting date with both fixed and flex budgets
            $transaction = $this->createExpense($user, $dateBeforeStartingDate, 25, 1, $this->accounts[1]);
            $this->addBudgetsToTransaction($transaction, $this->mixedBudgetIds);

            //Create expenses after starting date with both fixed and flex budgets
            $transaction = $this->createExpense($user, $dateAfterStartingDate, 30, 0, $this->accounts[1]);
            $this->addBudgetsToTransaction($transaction, $this->mixedBudgetIds);

            //Create expenses with unassigned budget
            $transaction = $this->createExpense($user, $dateAfterStartingDate, 5, 1, $this->accounts[1]);
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
        $dateBeforeStartingDate = Config::get('budgets.dateBeforeStartingDateForIncomeTransactions');
        $dateAfterStartingDate = Config::get('budgets.dateAfterStartingDateForIncomeTransactions');

        $businessId = Budget::where('user_id', $user->id)->whereName('business')->value('id');
        $buskingId = Budget::where('user_id', $user->id)->whereName('busking')->value('id');
        $somethingId = Budget::where('user_id', $user->id)->whereName('something')->value('id');

        foreach (range(1, $num) as $index) {
            //Create fixed budget income before starting date for 'business' budget
            $transaction = $this->createIncome($user, $dateBeforeStartingDate, 100, 1, $this->accounts[0]);
            $this->addBudgetToTransaction($transaction, $businessId);

            //Create fixed budget income after starting date for 'business' budget
            $transaction = $this->createIncome($user, $dateAfterStartingDate, 200, 0, $this->accounts[0]);
            $this->addBudgetToTransaction($transaction, $businessId);

            //Create flex budget income before starting date for 'busking' budget
            $transaction = $this->createIncome($user, $dateBeforeStartingDate, 500, 1, $this->accounts[0]);
            $this->addBudgetToTransaction($transaction, $buskingId);

            //Create flex budget income after starting date for 'busking' budget
            $transaction = $this->createIncome($user, $dateAfterStartingDate, 1000, 0, $this->accounts[1]);
            $this->addBudgetToTransaction($transaction, $buskingId);

            //Create income with unassigned budget
            $transaction = $this->createIncome($user, $dateAfterStartingDate, 250, 1, $this->accounts[1]);
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
            ->pluck('id')->all();

        $fixedBudgetIds = $this->faker->randomElements($fixedBudgetIds, $this->faker->numberBetween(1,2));

        $flexBudgetIds = Budget::where('user_id', $user->id)
            ->where('type', 'flex')
            ->pluck('id')->all();

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
            ->pluck('id')->all();

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
            ->pluck('id')->all();

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
            ->pluck('id')->all();

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
            $transaction = $this->createIncome($user, Carbon::today()->subMonths(7)->format('Y-m-d'), 300, 1, $this->accounts[0]);
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
            $transaction = $this->createExpense($user, Carbon::today()->subMonths(4)->format('Y-m-d'), 50, 1, $this->accounts[0]);
        }
    }

    /**
     *
     * @param $user
     * @param $date
     * @param $total
     * @param $reconciled
     * @param $account
     * @return Transaction
     */
    private function createExpense($user, $date, $total, $reconciled, $account)
    {
        $merchant = $this->setMerchant();

        $transaction = new Transaction([
            'type' => 'expense',
            'date' => $date,
            'account_id' => $account->id,
            'description' => $this->faker->sentence(1),
            'merchant' => $merchant,
//            'total' => $this->faker->randomElement([5, 10, 15, 20]) * -1,
            'total' => $total * -1,
            'reconciled' => $reconciled,
            'allocated' => 0,
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s')
        ]);

        $transaction->user()->associate($user);
        $transaction->save();

        return $transaction;
    }

    /**
     *
     * @return mixed
     */
    private function setMerchant()
    {
        $this->expenseMerchantsIndex++;
        if ($this->expenseMerchantsIndex > count($this->expenseMerchants) - 1) {
            $this->expenseMerchantsIndex = 0;
        }

        return $this->expenseMerchants[$this->expenseMerchantsIndex];
    }

    /**
     *
     * @param $user
     * @param $date
     * @param $total
     * @param $reconciled
     * @param $account
     * @return Transaction
     */
    private function createIncome($user, $date, $total, $reconciled, $account)
    {
        $transaction = new Transaction([
            'type' => 'income',
            'date' => $date,
            'account_id' => $account->id,
            'description' => $this->faker->sentence(1),
            'merchant' => $this->faker->name(),
//            'total' => $this->faker->randomElement([100, 500, 1000, 2000]),
            'total' => $total,
            'reconciled' => $reconciled,
            'allocated' => 0,
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s'),
            'minutes' => 90
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
        $from_account = Account::whereUserId($user->id)->offset(1)->first();
        $to_account = Account::whereUserId($user->id)->first();

        $date = Config::get('budgets.dateBeforeStartingDateForIncomeTransactions');
        $total = 100;
        $description = $this->faker->sentence(1);

        $transaction = new Transaction([
            'type' => 'transfer',
            'date' => $date,
            'account_id' => $from_account->id,
            'description' => $description,
            'merchant' => NULL,
            'total' => $total * -1,
            'reconciled' => 0,
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
            'reconciled' => 0,
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