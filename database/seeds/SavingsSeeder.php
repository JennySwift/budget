<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;
use App\Models\Savings;

class SavingsSeeder extends Seeder {

	public function run()
	{
        Savings::truncate();

        $users = User::all();

        foreach($users as $user) {
            $savings = new Savings(['amount' => 50]);
            $savings->user()->associate($user);
            $savings->save();
        }
	}

}