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
		
		$faker = Faker::create();

		/**
		 * Objective:
		 */

		$tags = ['food', 'clothes', 'work', 'recreation', 'books', 'computer', 'gifts'];

		foreach (range(1, 7) as $index) {
			//Decide if the tag should have a budget
			$has_budget = $faker->boolean($chanceOfGettingTrue = 60);
			$tag = $faker->unique()->randomElement($tags);

			if ($has_budget) {
				//Tag should be given a budget. Decide what type of budget it should have
				$budget_type = $faker->randomElement(['fixed_budget', 'flex_budget']);

				if ($budget_type === 'fixed_budget') {
					$budget = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 200);
					$budget_id = 1;
				}
				elseif ($budget_type === 'flex_budget') {
					$budget = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100);
					$budget_id = 2;
				}

				$starting_date = $faker->date($format = 'Y-m-d', $max = 'now');

				//Create the tag with the budget, budget type, and starting date
				Tag::create([
					'name' => $tag,
					$budget_type => $budget,
					'starting_date' => $starting_date,
					'budget_id' => $budget_id,
					'user_id' => 1
				]);
			}

			else {
				//No budget for this tag. Create the tag.
				Tag::create([
					'name' => $tag,
					'user_id' => 1
				]);
			}
		}

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

}