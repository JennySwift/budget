<?php
	// include(app_path().'/inc/config.php');
	// echo 'firephp: ' . $firephp;
// /*========================================functions========================================*/

// /*========================================select========================================*/

function getTransaction ($db, $transaction_id) {
	//for the new transaction allocation popup. probably selecting things here that I don't actually need for just the popup.
	$sql = "SELECT allocation, transactions.id, date,type, transactions.account AS account_id, accounts.name AS account_name,IFNULL(merchant, '') AS merchant_clone,description,reconciled,total, DATE_FORMAT(date, '%d/%m/%Y') AS formatted_date FROM transactions JOIN accounts ON transactions.account = accounts.id WHERE transactions.id = $transaction_id;";
	
	require_once("../tools/FirePHPCore/FirePHP.class.php");
	ob_start();
	$firephp = FirePHP::getInstance(true);		
	$firephp->log($sql, 'sql');

	$sql_result = $db->query($sql);

	//much the same as the filter loop in select.php. perhaps make more DRY
	while($row = $sql_result->fetch(PDO::FETCH_ASSOC)) { 
	    $transaction_id = $row['id'];
	    $type = $row['type'];
	    $account_id = $row['account_id'];
	    $account_name = $row['account_name'];
	    $total = $row['total'];
	    $description = $row['description'];
	    $merchant = $row['merchant_clone'];
	    $reconciled = $row['reconciled'];
	    $allocation = $row['allocation'];
	    $user_date = $row['formatted_date'];

	    $reconciled = convertToBoolean($reconciled);
	    $allocation = convertToBoolean($allocation);

	    $date = array(
	        "user" => $user_date
	    );

	    $account = array(
	        "id" => $account_id,
	        "name" => $account_name
	    );

	    $total = number_format($total, 2, '.', '');
	    $formatted_total = number_format($total, 2);

	    $this_transactions_tags = getTags($db, $transaction_id);
	    $multiple_budgets = hasMultipleBudgets($db, $transaction_id);

	    $transaction = array(
	        "id" => $transaction_id,
	        "type" => $type,
	        "account" => $account,
	        "total" => $total,
	        "formatted_total" => $formatted_total,
	        "date" => $date,
	        "description" => $description,
	        "merchant" => $merchant,
	        "tags" => $this_transactions_tags,
	        "reconciled" => $reconciled,
	        "allocation" => $allocation,
	        "multiple_budgets" => $multiple_budgets
	    );      
	}
	return $transaction;
}

function getCMN ($starting_date) {
	//CMN is cumulative month number
	$php_starting_date = new DateTime($starting_date);

	$now = new DateTime('now');

	$diff = $now->diff($php_starting_date);

	$CMN = $diff->format('%y') * 12 + $diff->format('%m') + 1;

	// require_once("../tools/FirePHPCore/FirePHP.class.php");
	// ob_start();
	// $firephp = FirePHP::getInstance(true);		
	// $firephp->log($starting_date, 'starting_date');
	// $firephp->log($php_starting_date, 'php_starting_date');
	// $firephp->log($now, 'now');
	// $firephp->log($diff, 'diff');
	// $firephp->log($CMN, 'CMN');

	return $CMN;
}

function getTagsWithFixedBudget ($db, $user_id) {
	$sql = "SELECT id, name, fixed_budget, starting_date FROM tags WHERE flex_budget IS NULL AND fixed_budget IS NOT NULL AND user_id = $user_id ORDER BY name ASC;";
	$sql_result = $db->query($sql);

	$tags = array();
	while ($row = $sql_result->fetch(PDO::FETCH_ASSOC)) {
		$tags[] = $row;
	}
	return $tags;
}

function getTagsWithFlexBudget ($db, $user_id) {
	$sql = "SELECT id, name, fixed_budget, flex_budget, starting_date FROM tags WHERE flex_budget IS NOT NULL AND user_id = $user_id ORDER BY name ASC;";
	$sql_result = $db->query($sql);
	
	$tags = array();
	while ($row = $sql_result->fetch(PDO::FETCH_ASSOC)) {
		$tags[] = $row;
	}
	return $tags;
}

function getLastTransactionId ($db, $user_id) {
    $sql = "SELECT MAX(transactions.id) FROM transactions WHERE user_id = '$user_id'";
    $sql_result = $db->query($sql);
    $last_transaction_id = $sql_result->fetchColumn(); 
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

function getAllocationInfo ($db, $transaction_id, $tag_id) {
	//for one tag. for getting the updated info after updating the allocation for that tag.
	//this is much the same as getTags() so maybe make it more DRY.
	$sql = "SELECT transactions_tags.transaction_id, transactions_tags.tag_id, transactions_tags.allocated_percent, transactions_tags.allocated_fixed, transactions_tags.calculated_allocation, tags.name, tags.fixed_budget, tags.flex_budget FROM transactions_tags JOIN tags ON transactions_tags.tag_id = tags.id WHERE transaction_id = '$transaction_id' AND tag_id = $tag_id";
	$sql_result = $db->query($sql);

	while ($row = $sql_result->fetch(PDO::FETCH_ASSOC)) {
		$tag_id = $row['tag_id'];
		$tag_name = $row['name'];
		$fixed_budget = $row['fixed_budget'];
		$flex_budget = $row['flex_budget'];
		$allocated_fixed = $row['allocated_fixed'];
		$allocated_percent = $row['allocated_percent'];
		$calculated_allocation = $row['calculated_allocation'];

		if ($allocated_fixed && !$allocated_percent) {
			$allocation_type = 'fixed';
		}

		elseif ($allocated_percent && !$allocated_fixed) {
			$allocation_type = 'percent';
		}
		elseif (!$allocation_fixed && !$allocated_percent) {
			$allocation_type = undefined;
		}

		$allocation_info = array(
			"id" => $tag_id,
			"name" => $tag_name,
			"fixed_budget" => $fixed_budget,
			"flex_budget" => $flex_budget,
			"allocated_fixed" => $allocated_fixed,
			"allocated_percent" => $allocated_percent,
			"calculated_allocation" => $calculated_allocation,
			"allocation_type" => $allocation_type
		);
	}
	return $allocation_info;
}

function getTags ($transaction_id) {
	//gets tags for one transaction
	// $tags = DB::table('transactions_tags')
	// 					->join('tags', 'tag_id', '=', 'tags.id')
	// 					->select('transaction_id', 'tag_id', 'allocated_percent', 'allocated_fixed', 'calculated_allocation', 'tags.name', 'tags.fixed_budget', 'tags.flex_budget')
	// 					->where('transaction_id', '$transaction_id')
	// 					->get();
	$sql = "SELECT transactions_tags.transaction_id, transactions_tags.tag_id, transactions_tags.allocated_percent, transactions_tags.allocated_fixed, transactions_tags.calculated_allocation, tags.name, tags.fixed_budget, tags.flex_budget FROM transactions_tags JOIN tags ON transactions_tags.tag_id = tags.id WHERE transaction_id = '$transaction_id'";
	$tags = DB::select($sql);

	foreach ($tags as $tag) {
		$allocated_fixed = $tag->allocated_fixed;
		$allocated_percent = $tag->allocated_percent;

		if ($allocated_fixed && !$allocated_percent) {
			$tag->allocation_type = 'fixed';
		}
		elseif ($allocated_percent && !$allocated_fixed) {
			$tag->allocation_type = 'percent';
		}
		elseif (!$allocation_fixed && !$allocated_percent) {
			//this caused an error for some reason.
			// $tag->allocation_type = 'undefined';
		}
	}

	return $tags;
}

function filter ($filter) {
	// return 'hello from filter';
    $select = "SELECT allocated, transactions.id, date,type, transactions.account AS account_id, accounts.name AS account_name,IFNULL(merchant, '') AS merchant_clone,description,reconciled,total, DATE_FORMAT(date, '%d/%m/%Y') AS formatted_date";

    $where = " WHERE transactions.user_id = " . Auth::user()->id;

    foreach ($filter as $type => $value) {
        if ($value && $type === "accounts") {
            //==========type is accounts==========
            $accounts = $value;
            $where = $where . " AND (";
            $counter = 0;
            foreach ($accounts as $account_id) {
                $counter++;
                if ($counter < 2) {
                    //it's the first account in the array. no need for 'or'.
                    $where = $where . "account = $account_id";
                }
                else {
                    //need to use 'or'.
                    $where = $where . " OR account = $account_id";
                }
                
            }
            $where = $where . ")";
        }
        elseif ($value && $type === "types") {
            //==========type is type, ie, income, expense, transfer==========
            $types = $value;
            $where = $where . " AND (";
            $counter = 0;
            foreach ($types as $type) {
                $counter++;
                if ($counter < 2) {
                    //it's the first in the array. no need for 'or'.
                    $where = $where . "type = '$type'";
                }
                else {
                    //need to use 'or'.
                    $where = $where . " OR type = '$type'";
                }
                
            }
            $where = $where . ")";
        }
        elseif ($value && $type === "single_date_sql") {
            //==========dates==========
            $where = $where . " AND date = '$value'";
        }
        elseif ($value && $type === "from_date_sql") {
            $where = $where . " AND date >= '$value'";
        }
        elseif ($value && $type === "to_date_sql") {
            $where = $where . " AND date <= '$value'";
        }
        elseif ($value && $type === "total") {
            //==========total==========
            $where = $where . " AND total = $value";
        }
        elseif ($value && $type === "reconciled") {
            //==========reconciled==========
            if ($value !== "any") {
                $where = $where . " AND reconciled = '$value'";
            }
        }
        elseif ($value) {
            if ($type === "description" || $type === "merchant") {
                $where = $where . " AND transactions." . $type . " LIKE '%$value%'";
            }
        }
    }

    $join = "JOIN accounts ON transactions.account = accounts.id";

    $sql = "$select FROM transactions $join $where ORDER BY date DESC, id DESC;";
    $sql_result = DB::select($sql);

    $array = array();
    $transactions_with_tags = array();

    foreach ($sql_result as $row) {
    	$transaction_id = $row->id;
    	$type = $row->type;
    	$account_id = $row->account_id;
    	$account_name = $row->account_name;
    	$total = $row->total;
    	$description = $row->description;
    	$merchant = $row->merchant_clone;
    	$reconciled = $row->reconciled;
    	$allocation = $row->allocated;
    	$user_date = $row->formatted_date;

    	$reconciled = convertToBoolean($reconciled);
    	$allocation = convertToBoolean($allocation);

    	$date = array(
    	    "user" => $user_date
    	);

    	$account = array(
    	    "id" => $account_id,
    	    "name" => $account_name
    	);

    	$total = number_format($total, 2, '.', '');
    	$formatted_total = number_format($total, 2);

    	$this_transactions_tags = getTags($transaction_id);
    	$multiple_budgets = hasMultipleBudgets($transaction_id);

    	$array[] = array(
    	    "id" => $transaction_id,
    	    "type" => $type,
    	    "account" => $account,
    	    "total" => $total,
    	    "formatted_total" => $formatted_total,
    	    "date" => $date,
    	    "description" => $description,
    	    "merchant" => $merchant,
    	    "tags" => $this_transactions_tags,
    	    "reconciled" => $reconciled,
    	    "allocation" => $allocation,
    	    "multiple_budgets" => $multiple_budgets
    	);      
    }
    return $array;
}

// /*========================================insert========================================*/

function insertTags ($db, $user_id, $transaction_id, $tags) {
    foreach ($tags as $tag) {
    	$tag_id = $tag['id'];
    	$tag_allocated_fixed = $tag['allocated_fixed'];
    	$tag_allocated_percent = $tag['allocated_percent'];

        if (isset($tag_allocated_fixed)) {
            $sql = "INSERT INTO transactions_tags (transaction_id, tag_id, allocated_fixed, user_id)
                        VALUES ('$transaction_id', '$tag_id', '$tag_allocated_fixed', '$user_id')";
        }
        elseif (isset($tag_allocated_percent)) {
            $sql = "INSERT INTO transactions_tags (transaction_id, tag_id, allocated_percent, user_id)
                        VALUES ('$transaction_id', '$tag_id', '$tag_allocated_percent', '$user_id')";
        }
        else {
        	$sql = "INSERT INTO transactions_tags (transaction_id, tag_id, user_id)
        	            VALUES ('$transaction_id', '$tag_id', '$user_id')";
        }
        
        $db->query($sql);

        updateCalculatedAllocation($db, $transaction_id, $tag_id);
    } 
}

function insertTransaction ($db, $user_id, $new_transaction, $transfer_type) {
	$date = $new_transaction['date']['sql'];
	$description = $new_transaction['description'];
	$type = $new_transaction['type'];
	$reconciliation = $new_transaction['reconciled'];
	$reconciliation = formatReconciliation($reconciliation);
	$tags = $new_transaction['tags'];

	if ($transfer_type === "from") {
		$from_account = $new_transaction['from_account'];
		$negative_total = $new_transaction['negative_total'];

		$sql = "INSERT INTO transactions (account,date,total,description,type,reconciled,user_id)
				VALUES ('$from_account','$date','$negative_total',?,'$type','$reconciled','$user_id')";
		
		$result = $db->prepare($sql);
		$result->bindParam(1, $description);
		$result->execute();
	}
	elseif ($transfer_type === "to") {
		$to_account = $new_transaction['to_account'];
		$total = $new_transaction['total'];

		$sql = "INSERT INTO transactions (account,date,total,description,type,reconciled,user_id)
				VALUES ('$to_account','$date','$total',?,'$type','$reconciled','$user_id')";

		$result = $db->prepare($sql);
		$result->bindParam(1, $description);
		$result->execute();
	}
	elseif (!$transfer_type) {
		$account = $new_transaction['account'];
		$merchant = $new_transaction['merchant'];
		$total = $new_transaction['total'];

		$sql = "INSERT INTO transactions (account,date,merchant,total,description,type,reconciled,user_id)
				VALUES ('$account','$date',?,'$total',?,'$type','$reconciliation','$user_id')";
	
		$result = $db->prepare($sql);
		$result->bindParam(1, $merchant);
		$result->bindParam(2, $description);
		$result->execute();		
	}

	require_once("../tools/FirePHPCore/FirePHP.class.php");
	ob_start();
	$firephp = FirePHP::getInstance(true);		
	$firephp->log($sql, 'inserttransactionsql');

	//inserting tags
	$last_transaction_id = getLastTransactionId($db, $user_id);
	insertTags($db, $user_id, $last_transaction_id, $tags);
}

// /*========================================update========================================*/

function updateAllocatedFixed ($db, $allocated_fixed, $transaction_id, $tag_id) {
	$sql = "UPDATE transactions_tags SET allocated_fixed = $allocated_fixed, allocated_percent = null, calculated_allocation = $allocated_fixed WHERE transaction_id = $transaction_id AND tag_id = $tag_id;";
	$sql_result = $db->query($sql);
}

function updateAllocatedPercent ($db, $allocated_percent, $transaction_id, $tag_id) {
	$sql = "UPDATE transactions_tags SET allocated_percent = $allocated_percent, allocated_fixed = null WHERE transaction_id = $transaction_id AND tag_id = $tag_id;";
	$sql_result = $db->query($sql);
	updateCalculatedAllocation($db, $transaction_id, $tag_id);
}

function updateCalculatedAllocation ($db, $transaction_id, $tag_id) {
	//updates calculated_allocation column for one row in transactions_tags
	$sql = "UPDATE transactions_tags calculated_allocation JOIN transactions ON transactions.id = transaction_id SET calculated_allocation = transactions.total / 100 * allocated_percent WHERE transaction_id = $transaction_id AND tag_id = $tag_id;";
	$sql_result = $db->query($sql);
}

function updateAllocationStatus ($db, $transaction_id, $status) {
	if ($status == 1) {
		$status = 'true';
	}
	else {
		$status = 'false';
	}

	$sql = "UPDATE transactions SET allocation = '$status' WHERE id = $transaction_id";
	$sql_result = $db->query($sql);
}

// function updateCalculatedAllocation ($db) {
	//this does all rows
// 	$sql = "UPDATE transactions_tags calculated_allocation JOIN transactions ON transactions.id = transaction_id SET calculated_allocation = transactions.total / 100 * allocated_percent WHERE allocated_percent IS NOT NULL";
// 	$sql_result = $db->query($sql);
// }


// /*========================================delete========================================*/

function deleteAllTagsForTransaction ($db, $transaction_id) {
	$sql = "DELETE FROM transactions_tags WHERE transaction_id = $transaction_id;";
	$sql_result = $db->query($sql);
}

// /*========================================totals========================================*/

include('total-functions.php');

// /*========================================other========================================*/

function formatReconciliation ($reconciliation) {
    if ($reconciliation == 1) { //triple equals did not work here
        $reconciliation = 'true';
    }
    else {
        $reconciliation = 'false';
    }
    return $reconciliation;
}

function numberFormat ($array) {
	$formatted_array = array();
	foreach ($array as $key => $value) {
		$formatted_value = number_format($value, 2);
		$formatted_array[$key] = $formatted_value;
	}

	require_once("../tools/FirePHPCore/FirePHP.class.php");
	ob_start();
	$firephp = FirePHP::getInstance(true);		
	$firephp->log($formatted_array, 'formatted_array');

	return $formatted_array;
}

function convertToBoolean ($variable) {
	if ($variable === 'true') {
		$variable = true;
	}
	else {
		$variable = false;
	}
	return $variable;
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

?>
