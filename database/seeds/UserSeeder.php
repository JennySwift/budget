<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;

class UserSeeder extends Seeder {

	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

        if (app()->env === 'local') {
            User::truncate();
            $this->createUser('Dummy', 'cheezyspaghetti@gmail.com');
        }
        else {
            //Seeding production site. So that I don't delete all my users, but just the seeded user.
            User::whereEmail('cheezyspaghetti@optusnet.com.au')->delete();
            $this->createUser('Dummy', 'cheezyspaghetti@optusnet.com.au');
        }

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
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