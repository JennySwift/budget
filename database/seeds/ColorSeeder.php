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
            $this->insertColors(1);
        }
        else {
            $this->insertColors(User::whereEmail('cheezyspaghetti@optusnet.com.au')->id);
        }
	}

    private function insertColors($user_id)
    {
        $this->insertColor($user_id, 'income', '#017d00');
        $this->insertColor($user_id, 'expense', '#fb5e52');
        $this->insertColor($user_id, 'transfer', '#fca700');
    }

    private function insertColor($user_id, $type, $color)
    {
        $color = new Color([
            'item' => $type,
            'color' => $color
        ]);

        $color->user()->associate(User::find($user_id));
        $color->save();
    }
}