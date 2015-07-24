<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;

use App\Models\Tag;

class TagSeeder extends Seeder {

	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

        if (app()->env === 'local') {
            Tag::truncate();

            $this->insertFixedBudgetTags(1);
            $this->insertFlexBudgetTags(1);
            $this->insertNoBudgetTags(1);
        }
        else {
            $dummy = User::whereEmail('cheezyspaghetti@optusnet.com.au');
            $this->insertFixedBudgetTags($dummy->id);
            $this->insertFlexBudgetTags($dummy->id);
            $this->insertNoBudgetTags($dummy->id);
        }

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

    private function insertFixedBudgetTags($user_id)
    {
        $faker = Faker::create();
        $fixed_budget_tags = ['insurance', 'petrol'];

        foreach ($fixed_budget_tags as $tag) {
            Tag::create([
                'name' => $tag,
                'fixed_budget' => $faker->randomElement([10, 20, 50, 100]),
                'starting_date' => '2015-01-01',
                'budget_id' => 1,
                'user_id' => $user_id
            ]);
        }
    }

    private function insertFlexBudgetTags($user_id)
    {
        $faker = Faker::create();
        $flex_budget_tags = ['recreation', 'clothes'];

        foreach ($flex_budget_tags as $tag) {
            Tag::create([
                'name' => $tag,
                'flex_budget' => $faker->randomElement([10, 20, 50]),
                'starting_date' => '2015-01-01',
                'budget_id' => 2,
                'user_id' => $user_id
            ]);
        }
    }

    private function insertNoBudgetTags($user_id)
    {
        $no_budget_tags = ['bank fees'];
        foreach ($no_budget_tags as $tag) {
            Tag::create([
                'name' => $tag,
                'user_id' => $user_id
            ]);
        }
    }
}