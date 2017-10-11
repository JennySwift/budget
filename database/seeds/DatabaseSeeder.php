<?php

use App\Models\Account;
use App\Models\Budget;
use App\Models\FavouriteTransaction;
use App\Models\SavedFilter;
use App\Models\Savings;
use App\Models\Transaction;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $this->truncate();
        $this->call('UserSeeder');
        $this->call('SavingsSeeder');
        $this->call('BudgetSeeder');
        $this->call('AccountSeeder');
        $this->call('TransactionSeeder');
        $this->call('FavouriteTransactionsSeeder');
        $this->call('FilterSeeder');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Model::reguard();
    }

    private function truncate()
    {
        User::truncate();
        Savings::truncate();
        Budget::truncate();
        Account::truncate();
        Transaction::truncate();
        FavouriteTransaction::truncate();
        SavedFilter::truncate();
        DB::table('budgets_favourite_transactions')->truncate();
        DB::table('budgets_transactions')->truncate();
    }
}
