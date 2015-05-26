<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Color;

class ColorSeeder extends Seeder {

	public function run()
	{
		Color::truncate();

		/**
		 * Objective:
		 */
		
		Color::create([
			'item' => 'income',
			'color' => '#017d00',
			'user_id' => 1
		]);
		Color::create([
			'item' => 'expense',
			'color' => '#fb5e52',
			'user_id' => 1
		]);
		Color::create([
			'item' => 'transfer',
			'color' => '#fca700',
			'user_id' => 1
		]);	
	}

}