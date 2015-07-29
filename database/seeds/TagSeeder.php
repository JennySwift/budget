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
            $fixed_budget_tags = $this->getFixedBudgetTags(1);
        }
        else {
            $fixed_budget_tags = $this->getFixedBudgetTags(1);
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
            $flex_budget_tags = $this->getFlexBudgetTags(1);
        }
        else {
            $flex_budget_tags = $this->getFlexBudgetTags(1);
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

    private function getFlexBudgetTags($quantity)
    {
        $array = [
            'eating out',
            'entertainment',
            'recreation',
            'holidays',
            'gifts',
            'books',
            'clothes',
            'church',
            'equipment',
            'guitar',
            'health',
            'miscellaneous',
            'music',
            'superannuation',
            'tax'
        ];

        //In order to have the specified $quantity of tags
        $tags = [];
        foreach (range(1, $quantity) as $index) {
            $tags[] = $array[$index -1];
        }

        return $tags;
    }

    private function getFixedBudgetTags($quantity)
    {
        $array = [
            'groceries',
            'rent',
            'licenses',
            'insurance',
            'conferences',
            'car',
            'mobile phone',
            'petrol',
            'sport'
        ];

        //In order to have the specified $quantity of tags
        $tags = [];
        foreach (range(1, $quantity) as $index) {
            $tags[] = $array[$index -1];
        }

        return $tags;
    }
}