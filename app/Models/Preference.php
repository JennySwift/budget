<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model {

	protected $fillable = ['type', 'value'];

	/**
	 * Define relationships
	 */
	
	public function user()
	{
	    return $this->belongsTo('App\User');
	}

}
