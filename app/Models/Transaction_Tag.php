<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction_Tag extends Model {

	//specifying the table to use
	protected $table = 'transactions_tags';

	// protected $fillable = ['transaction_id', 'tag_id', 'allocated_fixed', 'allocated_percent', 'calculated_allocation', 'user_id'];
	protected $fillable = ['transaction_id', 'tag_id', 'allocated_fixed', 'allocated_percent', 'calculated_allocation'];

}
