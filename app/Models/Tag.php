<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Tag extends Model {

	//
	// protected $fillable = ['name', 'fixed_budget', 'flex_budget', 'starting_date', 'user_id'];

	/**
	 * define relationships
	 */

	public function transactions () {
		return $this->belongsToMany('App\Models\Transaction', 'transactions_tags');
	}

	public function budget () {
		return $this->belongsTo('App\Models\Budget');
	}

	/**
	 * select
	 */
	
	public static function getTagsWithFixedBudget($user_id)
	{
		$sql = "SELECT id, name, fixed_budget, starting_date FROM tags WHERE flex_budget IS NULL AND fixed_budget IS NOT NULL AND user_id = $user_id ORDER BY name ASC;";
		$tags = DB::select($sql);

		return $tags;
	}

	public static function getTagsWithFlexBudget($user_id)
	{
		$sql = "SELECT id, name, fixed_budget, flex_budget, starting_date FROM tags WHERE flex_budget IS NOT NULL AND user_id = $user_id ORDER BY name ASC;";
		$tags = DB::select($sql);
		
		return $tags;
	}

	public static function getTags($transaction_id)
	{
		//gets tags for one transaction
		$tags = Transaction_Tag::
			join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
			->select('transactions_tags.tag_id AS id', 'transactions_tags.allocated_percent', 'transactions_tags.allocated_fixed', 'transactions_tags.calculated_allocation', 'tags.name', 'tags.fixed_budget', 'tags.flex_budget')
			->where('transaction_id', $transaction_id)
			->get();

		$tags = $tags->toArray();	
		

		foreach ($tags as $tag) {
			// Debugbar::info('allocated_fixed: ' . $tag['allocated_fixed']);
			// Debugbar::info('allocated_percent: ' . $tag['allocated_percent']);
			$allocated_fixed = $tag['allocated_fixed'];
			$allocated_percent = $tag['allocated_percent'];

			if (isset($allocated_fixed) && !$allocated_percent) {
				$tag['allocation_type'] = 'fixed';
			}
			elseif ($allocated_percent && !$allocated_fixed) {
				$tag['allocation_type'] = 'percent';
			}
			elseif (!isset($allocation_fixed) && !$allocated_percent) {
				//this caused an error for some reason.
				// $tag->allocation_type = 'undefined';
			}
		}

		return $tags;
	}

	/**
	 * insert
	 */
	
	
	
	/**
	 * update
	 */
	
	/**
	 * delete
	 */
}
