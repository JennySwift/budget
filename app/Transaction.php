<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

	//
	// protected $fillable = ['date', 'type', 'description', 'merchant', 'total', 'account', 'reconciled', 'allocated', 'user_id'];
	protected $fillable = ['description', 'merchant', 'account', 'reconciled', 'allocated'];

	public function tags () {
		return $this->belongsToMany('App\Tag', 'transactions_tags');
	}

	// public function budgets () {
	// 	return $this->tags->hasMany('App\Budget');
	// }
}
