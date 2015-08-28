<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Color;

class ColorSeeder extends Seeder {

    protected $colors = [
        [ 'item' => 'income', 'color' => '#017d00' ],
        [ 'item' => 'expense', 'color' => '#fb5e52' ],
        [ 'item' => 'transfer', 'color' => '#fca700' ]
    ];

	public function run()
	{
        Color::truncate();

        $users = User::all();

        foreach($users as $user) {
            foreach($this->colors as $color) {
                $color = new Color($color);
                $color->user()->associate($user);
                $color->save();
            }
        }
	}
}