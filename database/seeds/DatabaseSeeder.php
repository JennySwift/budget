<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Model::unguard();

		$this->call('UserTableSeeder');

		//this is what terminal will say to me
		$this->command->info('User table seeded!');
	}

}

class UserTableSeeder extends Seeder {

	public function run()
	{
		// Model::unguard();

		User::create([
			'name' => 'John',
			'email' => 'john@gmail.com',
			'password' => 'secret'
		]);

		// $faker = Faker::create();

		// foreach (range(1, 50) as $index) {
		// 	User::create([
		// 		'name' => $faker->word,
		// 		'email' => $faker->email,
		// 		'password' => 'secret'
		// 	]);
		// }
	}

}
