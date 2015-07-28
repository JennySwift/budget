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
            $user = User::whereEmail('cheezyspaghetti@gmail.com')->first();

            $this->insertFixedBudgetTags($user);
            $this->insertFlexBudgetTags($user);
            $this->insertNoBudgetTags($user);
        }
        else {
            $dummy = User::whereEmail('cheezyspaghetti@optusnet.com.au')->first();
            $this->insertFixedBudgetTags($dummy);
            $this->insertFlexBudgetTags($dummy);
            $this->insertNoBudgetTags($dummy);
        }

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

    private function insertFixedBudgetTags($user)
    {
        $faker = Faker::create();

        if (app()->env === 'local') {
            $fixed_budget_tags = ['insurance', 'petrol'];
        }
        else {
            $fixed_budget_tags = [
                'one',
                'two',
                'three',
                'four',
                'five',
                'six',
                'seven',
                'eight',
                'nine',
                'ten',
            ];
        }


        foreach ($fixed_budget_tags as $tag) {
            $tag = new Tag([
                'name' => $tag,
                'fixed_budget' => $faker->randomElement([10, 20, 50, 100]),
                'starting_date' => '2015-01-01',
                'budget_id' => 1,
            ]);

            $tag->user()->associate($user);
            $tag->save();
        }
    }

    private function insertFlexBudgetTags($user)
    {
        $faker = Faker::create();

        if (app()->env === 'local') {
            $flex_budget_tags = ['recreation', 'clothes'];
        }
        else {
            $flex_budget_tags = [
                'eleven',
                'twelve',
                'thirteen',
                'fourteen',
                'fifteen',
                'sixteen',
                'seventeen',
                'eighteen',
                'nineteen',
                'twenty',
                'twenty-one',
                'twenty-two',
                'twenty-three',
                'twenty-four',
                'twenty-five',
                'twenty-six',
                'twenty-seven',
                'twenty-eight',
                'twenty-nine',
                'thirty'
            ];
        }

        foreach ($flex_budget_tags as $tag) {
            $tag = new Tag([
                'name' => $tag,
                'flex_budget' => $faker->randomElement([10, 20, 50]),
                'starting_date' => '2015-01-01',
                'budget_id' => 2
            ]);

            $tag->user()->associate($user);
            $tag->save();
        }
    }

    private function insertNoBudgetTags($user)
    {
        $no_budget_tags = ['bank fees'];
        foreach ($no_budget_tags as $tag) {
            $tag = new Tag([
                'name' => $tag
            ]);

            $tag->user()->associate($user);
            $tag->save();
        }
    }
}