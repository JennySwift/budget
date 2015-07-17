<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;

class UserSeeder extends Seeder {

	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		User::truncate();
		
		User::create([
			'name' => 'Dummy1',
			'email' => 'cheezyspaghetti@gmail.com',
			'password' => bcrypt('abcdefg'),
            'settings' => [
                'income' => 'green',
                'expense' => 'red',
                'transfer' => 'orange'
            ]
		]);

		User::create([
			'name' => 'Dummy2',
			'email' => 'cheezyspaghetti@optusnet.com.au',
			'password' => bcrypt('hijklmnop'),
            'settings' => [
                'income' => 'green',
                'expense' => 'red',
                'transfer' => 'orange'
            ]
		]);

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}