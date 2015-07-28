<?php

use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;

class UserSeeder extends Seeder {

	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

        if (app()->env === 'local') {
//            $this->seedLocalUser();
            $this->seedProductionUser();
        }
        else {
            $this->seedProductionUser();
        }

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

    private function seedLocalUser()
    {
        User::truncate();
        $this->createUser('Dummy', 'cheezyspaghetti@gmail.com');
    }

    /**
     * Seeding production site.
     * So that I don't delete all my users, but just the seeded user.
     * Doing the if local check here to make sure I don't delete my real account
     * if I was to forget to change the email address of $dummy.
     */
    private function seedProductionUser()
    {
        if (app()->env === 'local') {
            $dummy = User::whereEmail('cheezyspaghetti@gmail.com')->first();
        }
        else {
            $dummy = User::whereEmail('cheezyspaghetti@optusnet.com.au')->first();
        }

        if ($dummy) {
            $this->deleteProductionDummyUser($dummy);
        }

        if (app()->env === 'local') {
            $this->createUser('Dummy', 'cheezyspaghetti@gmail.com');
        }
        else {
            $this->createUser('Dummy', 'cheezyspaghetti@optusnet.com.au');
        }
    }

    private function deleteProductionDummyUser($dummy)
    {
        $dummy->transactions()->delete();
        $dummy->accounts()->delete();
        $dummy->colors()->delete();
        $dummy->savings()->delete();
        $dummy->tags()->delete();
        $dummy->delete();
    }

    private function createUser($name, $email)
    {
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('abcdefg'),
            'settings' => [
                'income' => 'green',
                'expense' => 'red',
                'transfer' => 'orange'
            ]
        ]);
    }


}