<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Budget extends Model {

	//
	// protected $fillable = ['type'];

	/**
	 * define relationships
	 */

	public function tags () {
		return $this->hasMany('App\Tag');
	}
	
	/**
	 * select
	 */
	
	public static function getCMN($starting_date)
	{
		//CMN is cumulative month number
		$php_starting_date = new DateTime($starting_date);

		$now = new DateTime('now');

		$diff = $now->diff($php_starting_date);

		$CMN = $diff->format('%y') * 12 + $diff->format('%m') + 1;

		return $CMN;
	}

	public static function hasMultipleBudgets($transaction_id)
	{
		$sql = "SELECT tags.fixed_budget, tags.flex_budget FROM transactions_tags JOIN tags ON transactions_tags.tag_id = tags.id WHERE transaction_id = '$transaction_id'";
		$tags = DB::select($sql);

		$tag_with_budget_counter = 0;
		$multiple_budgets = false;

		foreach ($tags as $tag) {
			$fixed_budget = $tag->fixed_budget;
			$flex_budget = $tag->flex_budget;

			if ($fixed_budget || $flex_budget) {
				//the tag has a budget
				$tag_with_budget_counter++;
			}
		}

		if ($tag_with_budget_counter > 1) {
			//the transaction has more than one tag that has a budget
			$multiple_budgets = true;
		}

		return $multiple_budgets;
	}

	public static function getAllocationInfo($transaction_id, $tag_id)
	{
		//for one tag. for getting the updated info after updating the allocation for that tag.
		//this is much the same as getTags() so maybe make it more DRY.
		$allocation_info = DB::table('transactions_tags')
			->where('transaction_id', $transaction_id)
			->where('tag_id', $tag_id)
			->join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
			->select('transactions_tags.transaction_id', 'transactions_tags.tag_id AS id', 'transactions_tags.allocated_percent', 'transactions_tags.allocated_fixed', 'transactions_tags.calculated_allocation', 'tags.name', 'tags.fixed_budget', 'tags.flex_budget')
			->first();

		if (isset($allocation_info->allocated_fixed)) {
			$allocated_fixed = $allocation_info->allocated_fixed;
			$allocated_percent = false;
			$allocation_type = 'fixed';
		}

		elseif (isset($allocation_info->allocated_percent)) {
			$allocated_percent = $allocation_info->allocated_percent;
			$allocated_fixed = false;
			$allocation_type = 'percent';
		}
		else {
			$allocation_type = false;
			$allocated_fixed = false;
			$allocated_percent = false;
		}

		// Debugbar::info('row', $allocation_info);
		// $name = $row['name'];
		// Debugbar::info('name: ' . $name);

		// $allocation_info = array(
		// 	'transaction_id' => $transaction_id,
		// 	'tag_id' => $tag_id,
		// 	'allocated_fixed' => $allocated_fixed,
		// 	'allocated_percent' => $allocated_percent,
		// 	'calculated_allocation' => $row['calculated_allocation'],
		// 	'name' => $row['name'],
		// 	'fixed_budget' => $row['fixed_budget'],
		// 	'flex_budget' => $row['flex_budget'],
		// 	'allocation_type' => $row['allocation_type']
		// );

		// // $allocation_info['allocation_type'] = $allocation_type;
		
		return $allocation_info;
	}

	/**
	 * insert
	 */

	/**
	 * update
	 */
	
	public static function updateBudget($tag_id, $budget, $column)
	{
		Debugbar::info('budget: ' . $budget . ' column: ' . $column);
		//this either adds or deletes a budget, both using an update query.
		if (!$budget || $budget === "NULL") {
			$budget = NULL;
			$budget_id = NULL;
		}
		else {
			if ($column === "fixed_budget") {
				$budget_id = 1;
			}
			else {
				$budget_id = 2;
			}
		}
		
		DB::table('tags')
			->where('id', $tag_id)
			->update([$column => $budget, 'budget_id' => $budget_id]);
		// $queries = DB::getQueryLog();
		// Log::info('budget: ' . $budget);
		// Log::info('queries', $queries);
	}

	public static function updateAllocatedFixed($allocated_fixed, $transaction_id, $tag_id)
	{
		DB::table('transactions_tags')
			->where('transaction_id', $transaction_id)
			->where('tag_id', $tag_id)
			->update(['allocated_fixed' => $allocated_fixed, 'allocated_percent' => null, 'calculated_allocation' => $allocated_fixed]);
	}

	public static function updateAllocatedPercent($allocated_percent, $transaction_id, $tag_id)
	{
		DB::table('transactions_tags')
			->where('transaction_id', $transaction_id)
			->where('tag_id', $tag_id)
			->update(['allocated_percent' => $allocated_percent, 'allocated_fixed' => null]);

		updateAllocatedPercentCalculatedAllocation($transaction_id, $tag_id);
	}

	public static function updateAllocatedPercentCalculatedAllocation($transaction_id, $tag_id)
	{
		//updates calculated_allocation column for one row in transactions_tags, where the tag has been given an allocated percent
		$sql = "UPDATE transactions_tags calculated_allocation JOIN transactions ON transactions.id = transaction_id SET calculated_allocation = transactions.total / 100 * allocated_percent WHERE transaction_id = $transaction_id AND tag_id = $tag_id;";
		DB::update($sql);
	}

	public static function updateAllocationStatus($transaction_id, $status)
	{
		// if ($status == 1) {
		// 	$status = 'true';
		// }
		// else {
		// 	$status = 'false';
		// }
		DB::table('transactions')
			->where('id', $transaction_id)
			->update(['allocated' => $status]);
	}

	// public static function updateCalculatedAllocation($db)
	// {
		//this does all rows
	// 	$sql = "UPDATE transactions_tags calculated_allocation JOIN transactions ON transactions.id = transaction_id SET calculated_allocation = transactions.total / 100 * allocated_percent WHERE allocated_percent IS NOT NULL";
	// 	$sql_result = $db->query($sql);
	// }

	/**
	 * delete
	 */
	
}
