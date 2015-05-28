<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Carbon\Carbon;

use App\Models\Tag;

class Transaction extends Model {

	//
	// protected $fillable = ['date', 'type', 'description', 'merchant', 'total', 'account', 'reconciled', 'allocated', 'user_id'];
	protected $fillable = ['description', 'merchant', 'account', 'reconciled', 'allocated'];

	/**
	 * define relationships
	 */

	public function tags () {
		return $this->belongsToMany('App\Models\Tag', 'transactions_tags');
	}

	/**
	 * select
	 */

	public static function countTransactions()
	{
		$count = DB::table('transactions')->where('user_id', Auth::user()->id)->count();
		return $count;
	}

	public static function getTransaction($transaction_id)
	{
		//for the new transaction allocation popup. probably selecting things here that I don't actually need for just the popup.
		$transaction = DB::table('transactions')
			->where('transactions.id', $transaction_id)
			->join('accounts', 'transactions.account_id', '=', 'accounts.id')
			->select('allocated', 'transactions.id', 'date', 'type', 'transactions.account_id AS account_id', 'accounts.name AS account_name', 'merchant', 'description', 'reconciled', 'total', 'date')
			->first();
		
		$date = $transaction->date;
		$transaction->user_date = static::convertDate($date, 'user');
		
		//maybe make more DRY-I think much the same as filter

		// $transaction['reconciled'] = convertToBoolean($reconciled);
		// $transaction['allocation'] = convertToBoolean($allocation);

		// Log::info('transaction', $transaction);

		// $account = array(
		//     "id" => $transaction['account_id'],
		//     "name" => $transaction['account_name']
		// );

		// $total = number_format($total, 2, '.', '');
		// $formatted_total = number_format($total, 2);

		// $this_transactions_tags = getTags($db, $transaction_id);
		// $multiple_budgets = hasMultipleBudgets($db, $transaction_id);

		// $transaction['account'] = $account;
		// $transaction['formatted_total'] = $formatted_total;
		// $transaction['tags'] = $this_transactions_tags;
		// $transactoin['multiple_budgets'] = $multiple_budgets;

		return $transaction;
	}

	public static function getLastTransactionId()
	{
		$last_transaction_id = DB::table('transactions')->where('user_id', Auth::user()->id)->max('id');
	    return $last_transaction_id;
	}

	public static function autocompleteTransaction($column, $typing)
	{
		$transactions = DB::table('transactions')
			->where($column, 'LIKE', $typing)
			->where('transactions.user_id', Auth::user()->id)
			->join('accounts', 'transactions.account_id', '=', 'accounts.id')
			->select('transactions.id', 'total', 'account_id', 'accounts.name AS account_name', 'type', 'description', 'merchant')
			// ->distinct()
			->limit(50)
			->orderBy('date', 'desc')
			->orderBy('id', 'desc')
			->get();

			// $queries = DB::getQueryLog();
			// Log::info('queries', $queries);

		foreach($transactions as $transaction) {
			$transaction_id = $transaction->id;
		    $account_id = $transaction->account_id;
		    $account_name = $transaction->account_name;
		    // $date = $transaction->date;
		    // $date = convertDate($date, 'user');
		    $tags = Tag::getTags($transaction_id);

		    $account = array(
		        "id" => $account_id,
		        "name" => $account_name
		    );

		    $transaction->account = $account;
		    // $transaction->date = $date;
		    $transaction->tags = $tags;   
		}
		return $transactions;
	}

	// public static function removeNearDuplicates($transactions)
	// {
	// 	foreach ($transactions as $transaction) {
	// 		$id = $transaction['id'];
	// 		$description = $transaction['description'];
	// 		$merchant = $transaction['merchant'];
	// 		$total = $transaction['total'];
	// 		$type = $transaction['type'];
	// 		$account = $transaction['account'];
	// 		$from_account = $transaction['from_account'];
	// 		$to_account = $transaction['to_account'];

	// 		$object_1;

	// 		if ($type === 'transfer') {
	// 			$object_1 = array(
	// 				"description" => $description,
	// 				"total" => $total,
	// 				"from_account" => $from_account,
	// 				"to_account" => $to_account
	// 			);
	// 		}
	// 		else {
	// 			$object_1 = array(
	// 				"description" => $description,
	// 				"merchant" => $merchant,
	// 				"total" => $total,
	// 				"type" => $type,
	// 				"account" => $account
	// 			);
	// 		}

	// 		//we have the properties that we don't want to be duplicates in an object. now we loop through the array again to make another object, then we can compare if the two objects are equal.
	// 		foreach ($transactions as $t) {
	// 			 $index = $transactions.indexOf($t);
	// 			 $t_id = $t.id;
	// 			 $t_description = $t.description;
	// 			 $t_merchant = $t.merchant;
	// 			 $t_total = $t.total;
	// 			 $t_type = $t.type;
	// 			 $t_account = $t.account;
	// 			 $t_from_account = $t.from_account;
	// 			 $t_to_account = $t.to_account;

	// 			 $object_2 = {};

	// 			if ($t_id !== $id && $t_type === $type) {
	// 				//they are the same type, and not the same transaction
	// 				if ($type === 'transfer') {
	// 					$object_2 = {
	// 						description: $t_description,
	// 						total: $t_total,
	// 						from_account: $t_from_account,
	// 						to_account: $t_to_account
	// 					};
	// 				}
	// 				else {
	// 					$object_2 = {
	// 						description: $t_description,
	// 						merchant: $t_merchant,
	// 						total: $t_total,
	// 						type: $t_type,
	// 						account: $t_account
	// 					};
	// 				}
	// 			}

	// 			if (_.isEqual($object_1, $object_2)) {
	// 				$transactions.splice($index, 1);
	// 			}				
	// 		}
	// 	}
	// 	return $transactions;
	// }


	/**
	 * insert
	 */
	
	public static function insertTransaction($new_transaction, $transaction_type)
	{
		$user_id = Auth::user()->id;
		$date = $new_transaction['date']['sql'];
		$description = $new_transaction['description'];
		$type = $new_transaction['type'];
		$reconciled = $new_transaction['reconciled'];
		$reconciled = static::convertFromBoolean($reconciled);
		$tags = $new_transaction['tags'];

		if ($transaction_type === "from") {
			$from_account = $new_transaction['from_account'];
			$total = $new_transaction['negative_total'];

			DB::table('transactions')
				->insert([
					'account_id' => $from_account,
					'date' => $date,
					'total' => $total,
					'description' => $description,
					'type' => $type,
					'reconciled' => $reconciled,
					'user_id' => Auth::user()->id
				]);
		}
		elseif ($transaction_type === "to") {
			$to_account = $new_transaction['to_account'];
			$total = $new_transaction['total'];

			DB::table('transactions')
				->insert([
					'account_id' => $to_account,
					'date' => $date,
					'total' => $total,
					'description' => $description,
					'type' => $type,
					'reconciled' => $reconciled,
					'user_id' => Auth::user()->id
				]);
		}
		elseif ($transaction_type === 'income' || $transaction_type === 'expense') {
			$account = $new_transaction['account'];
			$merchant = $new_transaction['merchant'];
			$total = $new_transaction['total'];

			DB::table('transactions')
				->insert([
					'account_id' => $account,
					'date' => $date,
					'merchant' => $merchant,
					'total' => $total,
					'description' => $description,
					'type' => $type,
					'reconciled' => $reconciled,
					'user_id' => Auth::user()->id
				]);	
		}

		//inserting tags
		$last_transaction_id = static::getLastTransactionId($user_id);
		static::insertTags($last_transaction_id, $tags, $total);
	}

	/**
	 * Insert tags into transaction
	 * @param  [type] $transaction_id    [description]
	 * @param  [type] $tags              [description]
	 * @param  [type] $transaction_total [description]
	 * @return [type]                    [description]
	 */
	public static function insertTags($transaction_id, $tags, $transaction_total)
	{
		// Debugbar::info('transaction_total: ' . $transaction_total);
	    foreach ($tags as $tag) {
	    	$tag_id = $tag['id'];

	        if (isset($tag['allocated_fixed'])) {
	        	$tag_allocated_fixed = $tag['allocated_fixed'];
	        	$calculated_allocation = $tag_allocated_fixed;

	        	DB::table('transactions_tags')
	        		->insert([
	        			'transaction_id' => $transaction_id,
	        			'tag_id' => $tag_id,
	        			'allocated_fixed' => $tag_allocated_fixed,
	        			'calculated_allocation' => $calculated_allocation,
	        			'user_id' => Auth::user()->id
	        		]);

	        }
	        elseif (isset($tag['allocated_percent'])) {
	        	$tag_allocated_percent = $tag['allocated_percent'];
	        	$calculated_allocation = $transaction_total / 100 * $tag_allocated_percent;

	        	DB::table('transactions_tags')
	        		->insert([
	        			'transaction_id' => $transaction_id,
	        			'tag_id' => $tag_id,
	        			'allocated_percent' => $tag_allocated_percent,
	        			'calculated_allocation' => $calculated_allocation,
	        			'user_id' => Auth::user()->id
	        		]);

	        }
	        else {
	        	$calculated_allocation = $transaction_total;

	        	DB::table('transactions_tags')
	        		->insert([
	        			'transaction_id' => $transaction_id,
	        			'tag_id' => $tag_id,
	        			'calculated_allocation' => $calculated_allocation,
	        			'user_id' => Auth::user()->id
	        		]);
	        
	        }
	    } 
	}

	/**
	 * update
	 */
	
	public static function updateTransaction($transaction)
	{
		$transaction_id = $transaction['id'];
		$account_id = $transaction['account']['id'];
		$date = $transaction['date']['sql'];
		$merchant = $transaction['merchant'];
		$total = $transaction['total'];
		$tags = $transaction['tags'];
		$description = $transaction['description'];
		$type = $transaction['type'];
		$reconciliation = $transaction['reconciled'];
		$reconciliation = static::convertFromBoolean($reconciliation);

		DB::table('transactions')
			->where('id', $transaction_id)
			->update([
				'account_id' => $account_id,
				'type' => $type,
				'date' => $date,
				'merchant' => $merchant,
				'total' => $total,
				'description' => $description,
				'reconciled' => $reconciliation
			]);

		//delete all previous tags for the transaction and then add the current ones 
		static::deleteAllTagsForTransaction($transaction_id);

		static::insertTags($transaction_id, $tags, $total);
	}

	/**
	 * delete
	 */
	
	public static function deleteAllTagsForTransaction($transaction_id)
	{
		DB::table('transactions_tags')->where('transaction_id', $transaction_id)->delete();
	}

	/**
	 * other
	 */
	
	/**
	 * duplicate function from transactions controller
	 * @param  [type] $variable [description]
	 * @return [type]           [description]
	 */
	public static function convertFromBoolean($variable)
	{
	    if ($variable == 'true') {
	    	$variable = 1;
	    }
	    elseif ($variable == 'false') {
	    	$variable = 0;
	    }
	    return $variable;
	}

	public static function convertDate($date, $for) {
		if ($for === 'user') {
			$date = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/y');
		}
		elseif ($for === 'sql') {
			dd('elseif');
			$date = Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');

		}
		return $date;
	}
}
