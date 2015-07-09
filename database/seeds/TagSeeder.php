<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;

use App\Models\Tag;

class TagSeeder extends Seeder {

	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Tag::truncate();

		/**
		 * Objective:
		 */

//		$user_one_tags = ['insurance', 'petrol', 'food', 'clothes', 'work', 'recreation', 'books', 'computer', 'gifts'];
//		$user_two_tags = ['food', 'clothes', 'work', 'recreation', 'books', 'computer', 'gifts'];

//		$this->insertTags(1, $user_one_tags);
//		$this->insertTags(2, $user_two_tags);

        $faker = Faker::create();

        $fixed_budget_tags = ['insurance', 'petrol'];
        $flex_budget_tags = ['recreation', 'clothes'];
        $no_budget_tags = ['bank fees'];

        foreach ($fixed_budget_tags as $tag) {
            Tag::create([
                'name' => $tag,
                'fixed_budget' => $faker->randomElement([10, 20, 50, 100]),
                'starting_date' => '2015-01-01',
                'budget_id' => 1,
                'user_id' => 1
            ]);
        }

        foreach ($flex_budget_tags as $tag) {
            Tag::create([
                'name' => $tag,
                'flex_budget' => $faker->randomElement([10, 20, 50]),
                'starting_date' => '2015-01-01',
                'budget_id' => 2,
                'user_id' => 1
            ]);
        }

        foreach ($no_budget_tags as $tag) {
            Tag::create([
                'name' => $tag,
                'user_id' => 1
            ]);
        }

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

//	private function insertTags($user_id, $tags)
//	{
//		$faker = Faker::create();
//
//		foreach ($tags as $tag) {
//			//Decide if the tag should have a budget
//            if ($tag === 'food' || $tag === 'clothes' || $tag === 'recreation') {
//                $has_budget = true;
//            }
//            else {
//                $has_budget = false;
//            }



//			if ($user_id === 2) {
//				if ($tag === 'food' || $tag === 'clothes' || $tag === 'recreation') {
//					$has_budget = true;
//				}
//				else {
//					$has_budget = false;
//				}
//			}
//			else {
//				$has_budget = $faker->boolean($chanceOfGettingTrue = 60);
//			}
//
//			if ($has_budget) {
//				$this->insertTagWithBudget($user_id, $tag);
//			}
//
//			else {
//				$this->insertTagWithoutBudget($user_id, $tag);
//			}
//		}
//
//	}

	private function insertTagWithBudget($user_id, $tag)
	{
		$faker = Faker::create();

		//Tag should be given a budget. Decide what type of budget it should have
		if ($user_id === 2) {
			if ($tag === 'food' || $tag === 'clothes') {
				$budget_type = 'fixed_budget';
			}
			else {
				$budget_type = 'flex_budget';
			}
		}
		else {
			$budget_type = $faker->randomElement(['fixed_budget', 'flex_budget']);
		}

		// var_dump($budget_type);
		
		$budget = $faker->randomElement([10, 20, 50, 100]);

		if ($budget_type === 'fixed_budget') {
			$budget_id = 1;
		}
		elseif ($budget_type === 'flex_budget') {
			$budget_id = 2;
		}

		$starting_date = '2015-01-01';

		// $starting_date = $faker->date($format = 'Y-m-d', $min = '2014-01-01', $max = 'now');

		/**
		 * @VP:
		 * How do I use faker to create a date after a certain date? The $min parameter above didn't work.
		 * And with my attempt below, how do I access the actual date part of $test_date?
		 * Var_dump just showed the object.
		 */
		// $test_date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now');
		// $array = explode(' ', $test_date);
		// $test_date = $array[0];
		// var_dump($test_date);
		
		//Create the tag with the budget, budget type, and starting date
		Tag::create([
			'name' => $tag,
			$budget_type => $budget,
			'starting_date' => $starting_date,
			'budget_id' => $budget_id,
			'user_id' => $user_id
		]);
	}

	private function insertTagWithoutBudget($user_id, $tag)
	{
		Tag::create([
			'name' => $tag,
			'user_id' => $user_id
		]);
	}

}