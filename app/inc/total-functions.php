<?php

// DB::enableQueryLog();

function getTotalSpentOnTag ($tag_id, $starting_date) {
	//get total spent on a given tag after starting date
	$total = DB::table('transactions_tags')
		->join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
		->join('transactions', 'transactions_tags.transaction_id', '=', 'transactions.id')
		->where('transactions_tags.tag_id', $tag_id);

	if ($starting_date) {
		$total = $total->where('transactions.date', '>=', $starting_date);
	}	
		
	$total = $total
		->where('transactions.type', 'expense')
		->where('transactions_tags.user_id', Auth::user()->id)
		->sum('calculated_allocation');

	// $queries = DB::getQueryLog();
	// Log::info('queries', $queries);
	return $total;
}

function getTotalReceivedOnTag ($tag_id, $starting_date) {
	//get total received on a given tag after starting date
	$total = DB::table('transactions_tags')
		->join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
		->join('transactions', 'transactions_tags.transaction_id', '=', 'transactions.id')
		->where('transactions_tags.tag_id', $tag_id);

	if ($starting_date) {
		$total = $total->where('transactions.date', '>=', $starting_date);
	}
		
	$total = $total
		->where('transactions.type', 'income')
		->where('transactions_tags.user_id', Auth::user()->id)
		->sum('calculated_allocation');
	return $total;
}

function getTotalIncomeAfterDate ($db, $user_id, $cumulative_starting_date) {
	//gets the total income after the cumulative starting date
	$total_income = DB::table('transactions')
		->where('type', 'income')
		->where('transactions.date', '>=', $cumulative_starting_date)
		->where('user_id', Auth::user()->id)
		->sum('transactions.total');
	
	return $total_income;
}

function getReconciledSum () {
	//gets the sum of all transactions that are reconciled
	$reconciled_sum = DB::table('transactions')
		->where('reconciled', 1)
		->where('user_id', Auth::user()->id)
		->sum('total');

	return $reconciled_sum;
}

function getTotalIncome () {
	$sql_result = DB::table('transactions')
		->where('type', 'income')
		->where('user_id', Auth::user()->id)
		->lists('total');

    $total_income = 0;
    foreach ($sql_result as $transaction_total) {
    	$total_income+= $transaction_total;
    }

    return $total_income;
}

function getTotalExpense () {
	$sql_result = DB::table('transactions')
		->where('type', 'expense')
		->where('user_id', Auth::user()->id)
		->lists('total');

    $total_expense = 0;
    foreach ($sql_result as $transaction_total) {
    	$total_expense+= $transaction_total;
    }

    return $total_expense;
}

function getAllocationTotals ($transaction_id) {
	$row = DB::table('transactions_tags')
		->where('transaction_id', $transaction_id)
		->join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
		->select('transactions_tags.transaction_id', 'transactions_tags.tag_id', 'transactions_tags.allocated_percent', 'transactions_tags.allocated_fixed', 'transactions_tags.calculated_allocation', 'tags.name', 'tags.fixed_budget', 'tags.flex_budget')
		->get();

	$fixed_sum = '-';
	$percent_sum = 0;
	$calculated_allocation_sum = 0;

	// $allocated_fixed = $row['allocated_fixed'];
	// $allocated_percent = $row['allocated_percent'];
	// $calculated_allocation = $row['calculated_allocation'];

	// //so that the total displays '-' instead of $0.00 if there were no values to add up.
	// if ($allocated_fixed && $fixed_sum === '-') {
	// 	$fixed_sum = 0;
	// }
	
	// if ($allocated_fixed) {
	// 	$fixed_sum+= $allocated_fixed;
	// }

	// $percent_sum+= $allocated_percent;
	// $calculated_allocation_sum+= $calculated_allocation;

	if ($fixed_sum !== '-') {
		$fixed_sum = number_format($fixed_sum, 2);
	}
	
	$percent_sum = number_format($percent_sum, 2);
	$calculated_allocation_sum = number_format($calculated_allocation_sum, 2);

	$allocation_totals = array(
		"fixed_sum" => $fixed_sum,
		"percent_sum" => $percent_sum,
		"calculated_allocation_sum" => $calculated_allocation_sum
	);

	return $allocation_totals;
}

function getBudgetInfo ($user_id, $type) {
	if ($type === 'fixed') {
		$tags = getTagsWithFixedBudget($user_id);
	}
	elseif ($type === 'flex') {
		$tags = getTagsWithFlexBudget($user_id);
	}
	
	// We will be returning $budget_info.
	$budget_info = array(
		"each_tag" => array(),
		"totals" => array()
	);

	$total_budget = 0;

	if ($type === 'fixed') {
		$total_cumulative_budget = 0;
	}
	
	$total_spent = 0;
	$total_received = 0;
	$total_remaining = 0;

	foreach ($tags as $tag) {
		$tag_id = $tag->id;
		$tag_name = $tag->name;

		if ($type === 'fixed') {
			$budget = $tag->fixed_budget;
		}
		elseif ($type === 'flex') {
			$budget = $tag->flex_budget;
		}
		
		$CSD = $tag->starting_date;
		$CMN = getCMN($CSD);

		if ($type === 'fixed') {
			$cumulative_budget = $budget * $CMN;
		}
		
	    $spent = getTotalSpentOnTag($tag_id, $CSD);
	    $received = getTotalReceivedOnTag($tag_id, $CSD);		

		$total_budget += $budget;

		if ($type === 'fixed') {
			$remaining = $cumulative_budget + $spent + $received;
			$total_cumulative_budget += $cumulative_budget;
		}
		elseif ($type === 'flex') {
			$remaining = $budget + $spent + $received;
		}
		
		$total_spent += $spent;
		$total_received += $received;
		$total_remaining += $remaining;

		$CSD = convertDate($CSD, 'user');

		$budget = number_format($budget, 2);
		$spent = number_format($spent, 2);
		$received = number_format($received, 2);
		$remaining = number_format($remaining, 2);

		$tag_info = array(
			"id" => $tag_id,
			"name" => $tag_name,
			"budget" => $budget,
			"CSD" => $CSD,
			"CMN" => $CMN,
			"spent" => $spent,
			"received" => $received,
			"remaining" => $remaining
		);

		if ($type === 'fixed') {
			$cumulative_budget = number_format($cumulative_budget, 2);
			$tag_info['cumulative_budget'] = $cumulative_budget;
		}

		$budget_info['each_tag'][] = $tag_info;
	}

	$budget_info['totals'] = array(
		"budget" => $total_budget,
		"spent" => $total_spent,
		"received" => $total_received,
		"remaining" => $total_remaining
	);

	if ($type === 'fixed') {
		$budget_info['totals']['cumulative_budget'] = $total_cumulative_budget;
	}

	// //formatting the whole array
	$budget_info['totals'] = numberFormat($budget_info['totals']);

	return $budget_info;
	// return 'hello';
}

// function getFilterTotals ($transactions) {
//     $search_income = 0;
//     $search_expenses = 0;
//     $search_balance = 0;
//     $reconciled = 0;

//     foreach ($transactions as $transaction) {
//     	// Log::info('transactions', $transactions);
//         // $total = $transaction->total;
//         // $type = $transaction->type;
//         $total = $transaction['total'];
//         $type = $transaction['type'];

//         $reconciled += $total;

//         if ($type === "expense") {
//             $search_expenses += $total;
//         }

//         else if ($type === "income") {
//             $search_income += $total;
//         }
//         if ($type === "transfer") {
//             if ($total < 0) {
//                 $search_expenses += $total;
//             }
//             else if ($total > 0) {
//                 $search_income += $total;
//             }
//         }
//     }

//     $search_balance = $search_income + $search_expenses;

//     $search_income = number_format($search_income, 2);
//     $search_expenses = number_format($search_expenses, 2);
//     $search_balance = number_format($search_balance, 2);
//     $reconciled = number_format($reconciled, 2);

//     //get total number of transactions the user has
//     // $num_transactions = countTransactions();
//     $num_transactions = count($transactions);

//     $array = array(
//         "income" => $search_income,
//         "expenses" => $search_expenses,
//         "balance" => $search_balance,
//         "reconciled" => $reconciled,
//         "num_transactions" => $num_transactions
//     );

//     return $array;
// }

// function getASR ($transactions) {
//     $ASR = 0;

//     foreach ($transactions as $transaction) {
//         $total = $transaction['total'];
//         $type = $transaction['type'];

//         if ($type === "expense") {
//             $ASR += $total;
//         }
//         else if ($type === "income") {
//             $ASR += $total;
//         }
//         else if ($type === "transfer") {
//             $ASR += $total;
//         }
//     }

//     $ASR = number_format($ASR, 2);

//     return $ASR;
// }

?>