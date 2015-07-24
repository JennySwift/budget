<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;
use App\Models\Savings;

class SavingsSeeder extends Seeder {

	public function run()
	{
        if (app()->env === 'local') {
            Savings::truncate();
            $this->createSavingsForUser(User::whereEmail('cheezyspaghetti@gmail.com')->first());
        }
        else {
            $this->createSavingsForUser(User::whereEmail('cheezyspaghetti@optusnet.com.au')->first());
        }

	}

    private function createSavingsForUser($user)
    {
        $savings = new Savings(['amount' => 50]);
        $savings->user()->associate($user);
        $savings->save();
    }

}