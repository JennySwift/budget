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
		
		$this->insertColors(1);
		$this->insertColors(2);
		
	}

	private function insertColors($user_id)
	{
		Color::create([
			'item' => 'income',
			'color' => '#017d00',
			'user_id' => $user_id
		]);
		Color::create([
			'item' => 'expense',
			'color' => '#fb5e52',
			'user_id' => $user_id
		]);
		Color::create([
			'item' => 'transfer',
			'color' => '#fca700',
			'user_id' => $user_id
		]);	
	}

}