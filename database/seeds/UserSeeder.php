<?php

use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;

class UserSeeder extends Seeder {

    protected $users = [
        'Dummy' => 'cheezyspaghetti@gmail.com',
        'Dummy Bob' => 'bob@gmail.com',
    ];

	public function run()
	{
        foreach($this->users as $name => $email) {
            $this->createUser($name, $email);
        }
	}

    private function createUser($name, $email, $password = 'abcdefg')
    {
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'preferences' => [
                'colors' => [
                    'income' => Config::get('colors.income'),
                    'expense' => Config::get('colors.expense'),
                    'transfer' => Config::get('colors.transfer'),
                ],
                'clearFields' => false
            ]
        ]);
    }


}