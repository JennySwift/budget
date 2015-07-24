<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Color;

class ColorSeeder extends Seeder {

	public function run()
	{
        if (app()->env === 'local') {
            Color::truncate();
            $this->insertColors(User::whereEmail('cheezyspaghetti@gmail.com')->first());
        }
        else {
            $this->insertColors(User::whereEmail('cheezyspaghetti@optusnet.com.au')->first());
        }
	}

    private function insertColors($user)
    {
        $this->insertColor($user, 'income', '#017d00');
        $this->insertColor($user, 'expense', '#fb5e52');
        $this->insertColor($user, 'transfer', '#fca700');
    }

    private function insertColor($user, $type, $color)
    {
        $color = new Color([
            'item' => $type,
            'color' => $color
        ]);

        $color->user()->associate($user);
        $color->save();
    }
}