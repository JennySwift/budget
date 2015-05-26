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
			'password' => bcrypt('abcdefg')
		]);

		User::create([
			'name' => 'Dummy2',
			'email' => 'cheezyspaghetti@optusnet.com.au',
			'password' => bcrypt('hijklmnop')
		]);

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}