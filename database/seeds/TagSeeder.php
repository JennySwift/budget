<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;

use App\Models\Tag;

class TagSeeder extends Seeder {

    protected $howManyTags = 5;

    protected $tags = [
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

	public function run()
	{
        $users = User::all();

        foreach($users as $user) {
            foreach (array_slice($this->tags, 0, $this->howManyTags) as $tag) {
                $tag = new Tag([ 'name' => $tag ]);
                $tag->user()->associate($user);
                $tag->save();
            }
        }
	}

}