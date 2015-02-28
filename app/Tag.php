<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

	//
	protected $fillable = ['name', 'fixed_budget', 'flex_budget', 'starting_date', 'user_id'];
}
