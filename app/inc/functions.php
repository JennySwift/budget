<?php
	// include(app_path().'/inc/config.php');
	// echo 'firephp: ' . $firephp;

use App\Transaction_Tag;

// DB::enableQueryLog();

// /*========================================functions========================================*/

// /*========================================select========================================*/

function countTransactions () {
	$count = DB::table('transactions')->where('user_id', Auth::user()->id)->count();
	return $count;
}

function getTransaction ($transaction_id) {
	//for the new transaction allocation popup. probably selecting things here that I don't actually need for just the popup.
	$sql = "SELECT allocated, transactions.id, date,type, transactions.account_id AS account_id, accounts.name AS account_name,IFNULL(merchant, '') AS merchant,description,reconciled,total, DATE_FORMAT(date, '%d/%m/%Y') AS user_date FROM transactions JOIN accounts ON transactions.account_id = accounts.id WHERE transactions.id = $transaction_id;";
	$transaction = DB::select($sql);
	
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

function getTagsWithFixedBudget ($user_id) {
	$sql = "SELECT id, name, fixed_budget, starting_date FROM tags WHERE flex_budget IS NULL AND fixed_budget IS NOT NULL AND user_id = $user_id ORDER BY name ASC;";
	$tags = DB::select($sql);

	return $tags;
}

function getTagsWithFlexBudget ($user_id) {
	$sql = "SELECT id, name, fixed_budget, flex_budget, starting_date FROM tags WHERE flex_budget IS NOT NULL AND user_id = $user_id ORDER BY name ASC;";
	$tags = DB::select($sql);
	
	return $tags;
}

function getCMN ($starting_date) {
	//CMN is cumulative month number
	$php_starting_date = new DateTime($starting_date);

	$now = new DateTime('now');

	$diff = $now->diff($php_starting_date);

	$CMN = $diff->format('%y') * 12 + $diff->format('%m') + 1;

	return $CMN;
}

function convertDate ($date, $for) {
	$date = new DateTime($date);

	if ($for === 'user') {
		$date = $date->format('d/m/y');
	}
	elseif ($for === 'sql') {
		$date = $date->format('Y-m-d');
	}
	return $date;
}

function numberFormat ($array) {
	$formatted_array = array();
	foreach ($array as $key => $value) {
		$formatted_value = number_format($value, 2);
		$formatted_array[$key] = $formatted_value;
	}

	return $formatted_array;
}

function getLastTransactionId () {
	$last_transaction_id = DB::table('transactions')->where('user_id', Auth::user()->id)->max('id');
    return $last_transaction_id;
}

function hasMultipleBudgets ($transaction_id) {
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

function getAllocationInfo ($transaction_id, $tag_id) {
	//for one tag. for getting the updated info after updating the allocation for that tag.
	//this is much the same as getTags() so maybe make it more DRY.
	$allocation_info = DB::table('transactions_tags')
		->where('transaction_id', $transaction_id)
		->where('tag_id', $tag_id)
		->join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
		->select('transactions_tags.transaction_id', 'transactions_tags.tag_id', 'transactions_tags.allocated_percent', 'transactions_tags.allocated_fixed', 'transactions_tags.calculated_allocation', 'tags.name', 'tags.fixed_budget', 'tags.flex_budget')
		->get();

	$allocated_fixed = $row['allocated_fixed'];
	$allocated_percent = $row['allocated_percent'];

	if ($allocated_fixed && !$allocated_percent) {
		$allocation_type = 'fixed';
	}

	elseif ($allocated_percent && !$allocated_fixed) {
		$allocation_type = 'percent';
	}
	elseif (!$allocation_fixed && !$allocated_percent) {
		$allocation_type = undefined;
	}

	$allocation_info['allocation_type'] = $allocation_type;
	
	return $allocation_info;
}

function getTags ($transaction_id) {
	//gets tags for one transaction
	$tags = Transaction_Tag::
		join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
		->select('transactions_tags.tag_id AS id', 'transactions_tags.allocated_percent', 'transactions_tags.allocated_fixed', 'transactions_tags.calculated_allocation', 'tags.name', 'tags.fixed_budget', 'tags.flex_budget')
		->where('transaction_id', $transaction_id)
		->get();

	$tags = $tags->toArray();	
	

	foreach ($tags as $tag) {
		$allocated_fixed = $tag['allocated_fixed'];
		$allocated_percent = $tag['allocated_percent'];

		if ($allocated_fixed && !$allocated_percent) {
			$tag['allocation_type'] = 'fixed';
		}
		elseif ($allocated_percent && !$allocated_fixed) {
			$tag['allocation_type'] = 'percent';
		}
		elseif (!$allocation_fixed && !$allocated_percent) {
			//this caused an error for some reason.
			// $tag->allocation_type = 'undefined';
		}
	}

	return $tags;
}

include('filter-function.php');

function autocompleteTransaction ($column, $typing) {
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
	    $tags = getTags($transaction_id);

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

// function removeNearDuplicates ($transactions) {
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

// /*========================================insert========================================*/

function insertTags ($transaction_id, $tags) {
	// Log::info('tags', $tags);
    foreach ($tags as $tag) {
    	$tag_id = $tag['id'];

        if (isset($tag['allocated_fixed'])) {
        	$tag_allocated_fixed = $tag['allocated_fixed'];

        	DB::table('transactions_tags')
        		->insert([
        			'transaction_id' => $transaction_id,
        			'tag_id' => $tag_id,
        			'allocated_fixed' => $tag_allocated_fixed,
        			'user_id' => Auth::user()->id
        		]);

        }
        elseif (isset($tag['allocated_percent'])) {
        	$tag_allocated_percent = $tag['allocated_percent'];

        	DB::table('transactions_tags')
        		->insert([
        			'transaction_id' => $transaction_id,
        			'tag_id' => $tag_id,
        			'allocated_percent' => $tag_allocated_percent,
        			'user_id' => Auth::user()->id
        		]);

        }
        else {

        	DB::table('transactions_tags')
        		->insert([
        			'transaction_id' => $transaction_id,
        			'tag_id' => $tag_id,
        			'user_id' => Auth::user()->id
        		]);
        
        }
        
        updateCalculatedAllocation($transaction_id, $tag_id);
    } 
}

function insertTransaction ($new_transaction, $transaction_type) {
	$user_id = Auth::user()->id;
	$date = $new_transaction['date']['sql'];
	$description = $new_transaction['description'];
	$type = $new_transaction['type'];
	$reconciled = $new_transaction['reconciled'];
	$reconciled = convertFromBoolean($reconciled);
	$tags = $new_transaction['tags'];

	if ($transaction_type === "from") {
		$from_account = $new_transaction['from_account'];
		$negative_total = $new_transaction['negative_total'];

		DB::table('transactions')
			->insert([
				'account_id' => $from_account,
				'date' => $date,
				'total' => $negative_total,
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
	$last_transaction_id = getLastTransactionId($user_id);
	insertTags($last_transaction_id, $tags);
}

// /*========================================update========================================*/

function updateBudget ($tag_id, $budget, $column) {
	//this either adds or deletes a budget, both using an update query.
	if (!$budget || $budget === "NULL") {
		$budget = NULL;
		$budget_id = NULL;
	}
	else {
		if ($column === "fixed") {
			$budget_id = 1;
		}
		else {
			$budget_id = 2;
		}
	}
	
	DB::table('tags')->where('id', $tag_id)->update([$column => $budget, 'budget_id' => $budget_id]);
	// $queries = DB::getQueryLog();
	// Log::info('budget: ' . $budget);
	// Log::info('queries', $queries);
}

function updateAllocatedFixed ($allocated_fixed, $transaction_id, $tag_id) {
	DB::table('transactions_tags')
		->where('transaction_id', $transaction_id)
		->where('tag_id', $tag_id)
		->update(['allocated_fixed' => $allocated_fixed, 'allocated_percent' => null, 'calculated_allocation' => $allocated_fixed]);
}

function updateAllocatedPercent ($allocated_percent, $transaction_id, $tag_id) {
	DB::table('transactions_tags')
		->where('transaction_id', $transaction_id)
		->where('tag_id', $tag_id)
		->update(['allocated_percent' => $allocated_percent, 'allocated_fixed' => null]);

	updateCalculatedAllocation($transaction_id, $tag_id);
}

function updateCalculatedAllocation ($transaction_id, $tag_id) {
	//updates calculated_allocation column for one row in transactions_tags
	$sql = "UPDATE transactions_tags calculated_allocation JOIN transactions ON transactions.id = transaction_id SET calculated_allocation = transactions.total / 100 * allocated_percent WHERE transaction_id = $transaction_id AND tag_id = $tag_id;";
	DB::update($sql);
}

function updateAllocationStatus ($transaction_id, $status) {
	if ($status == 1) {
		$status = 'true';
	}
	else {
		$status = 'false';
	}
	DB::table('transactions')->where('id', $transaction_id)->update(['allocation' => $status]);
}

// function updateCalculatedAllocation ($db) {
	//this does all rows
// 	$sql = "UPDATE transactions_tags calculated_allocation JOIN transactions ON transactions.id = transaction_id SET calculated_allocation = transactions.total / 100 * allocated_percent WHERE allocated_percent IS NOT NULL";
// 	$sql_result = $db->query($sql);
// }


// /*========================================delete========================================*/

function deleteAllTagsForTransaction ($transaction_id) {
	DB::table('transactions_tags')->where('transaction_id', $transaction_id)->delete();
}

// /*========================================totals========================================*/

include('total-functions.php');

// /*========================================other========================================*/

function convertFromBoolean ($variable) {
    if ($variable == 'true') {
    	$variable = 1;
    }
    elseif ($variable == 'false') {
    	$variable = 0;
    }
    return $variable;
}



function convertToBoolean ($variable) {
	if ($variable === 1) {
		$variable = true;
	}
	else {
		$variable = false;
	}
	return $variable;
}



?>
