<?php

// DB::enableQueryLog();


function getBasicTotals () {
	$total_income = getTotalIncome();
	$total_expense = getTotalExpense();
	$balance = $total_income + $total_expense;
	$reconciled_sum = getReconciledSum();
	$savings_total = getSavingsTotal();
	$savings_balance = $balance - $savings_total;
	$expense_without_budget_total = getTotalExpenseWithoutBudget();
	$EFLB = getTotalExpenseWithFLB();
	
	$total_income = number_format($total_income, 2);
	$total_expense = number_format($total_expense, 2);
	$balance = number_format($balance, 2);
	$reconciled_sum = number_format($reconciled_sum, 2);
	$savings_total = number_format($savings_total, 2);
	$savings_balance = number_format($savings_balance, 2);
	$expense_without_budget_total = number_format($expense_without_budget_total, 2);
	$EFLB = number_format($EFLB, 2);

	$totals = array(
	    "total_income" => $total_income,
	    "total_expense" => $total_expense,
	    "balance" => $balance,
	    "reconciled_sum" => $reconciled_sum,
	    "savings_total" => $savings_total,
	    "savings_balance" => $savings_balance,
	    "expense_without_budget_total" => $expense_without_budget_total,
	    "EFLB" => $EFLB
	);
	return $totals;
}

function getBudgetTotals () {
	$user_id = Auth::user()->id;
	$FB_info = getBudgetInfo($user_id, 'fixed');
	$FLB_info = getBudgetInfo($user_id, 'flex');

	//calculating remaining balance
	$total_CFB = $FB_info['totals']['cumulative_budget'];
	$total_spent_before_CSD = $FB_info['totals']['spent_before_CSD'];
	$total_income = getTotalIncome();
	$total_savings = getSavingsTotal();
	$EWB = getTotalExpenseWithoutBudget();
	$EFLB = getTotalExpenseWithFLB();

	$remaining_balance = $total_income - $total_CFB + $EWB + $EFLB + $total_spent_before_CSD - $total_savings;
	$remaining_balance = number_format($remaining_balance, 2);

	//formatting
	$FB_info['totals'] = numberFormat($FB_info['totals']);
	
	$array = array(
	    "FB" => $FB_info,
	    "FLB" => $FLB_info,
	    "RB" => $remaining_balance
	);
	return $array;
}

function getTotalExpenseWithFLB () {
	//this is for calculating the remaining balance. Finds all transactions that have a flex budget and returns the total of those transactions.
	//first, get all the transactions that have a budget.
	$sql = "select id from transactions where transactions.type = 'expense' AND transactions.user_id = " . Auth::user()->id . " and (select count(*) from tags inner join transactions_tags on tags.id = transactions_tags.tag_id
	where transactions_tags.transaction_id = transactions.id
	and tags.budget_id = 2) > 0";

	$transactions_with_FLB = DB::select($sql);

	//format transactions_with_one_budget into a nice array
	$ids = array();
	foreach ($transactions_with_FLB as $transaction) {
	    $id = $transaction->id;
	    $ids[] = $id;
	}

	$total = DB::table('transactions')
		->whereIn('transactions.id', $ids)
		->sum('total');

	return $total;
}

function getTotalExpenseWithoutBudget () {
	//this is for calculating the remaining balance. Finds all transactions that have no budget and returns the total of those transactions.
	//first, get all the transactions that have no budget.
	$sql = "select id from transactions where transactions.type = 'expense' AND transactions.user_id = " . Auth::user()->id . " and (select count(*) from tags inner join transactions_tags on tags.id = transactions_tags.tag_id
	where transactions_tags.transaction_id = transactions.id
	and tags.budget_id is not null) = 0";

	$transactions_with_no_budgets = DB::select($sql);

	//format transactions_with_one_budget into a nice array
	$ids = array();
	foreach ($transactions_with_no_budgets as $transaction) {
	    $id = $transaction->id;
	    $ids[] = $id;
	}

	$total = DB::table('transactions')
		->whereIn('transactions.id', $ids)
		->sum('total');

	return $total;
}


function getSavingsTotal () {
	$savings = DB::table('savings')
		->where('user_id', Auth::user()->id)
		->pluck('amount');

	return $savings;
}

function getTotalSpentOnTagBeforeCSD ($tag_id, $CSD) {
	//get total spent on a given tag after starting date
	$total = DB::table('transactions_tags')
		->join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
		->join('transactions', 'transactions_tags.transaction_id', '=', 'transactions.id')
		->where('transactions_tags.tag_id', $tag_id);

	if ($CSD) {
		$total = $total->where('transactions.date', '<', $CSD);
	}	
		
	$total = $total
		->where('transactions.type', 'expense')
		->where('transactions_tags.user_id', Auth::user()->id)
		->sum('calculated_allocation');

	Debugbar::info('spent before CSD: ' . $total . ' tag_id: ' . $tag_id);

	return $total;
}

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
	$rows = DB::table('transactions_tags')
		->where('transaction_id', $transaction_id)
		->join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
		->select('transactions_tags.transaction_id', 'transactions_tags.tag_id', 'transactions_tags.allocated_percent', 'transactions_tags.allocated_fixed', 'transactions_tags.calculated_allocation', 'tags.name', 'tags.fixed_budget', 'tags.flex_budget')
		->get();

	$fixed_sum = '-';
	$percent_sum = 0;
	$calculated_allocation_sum = 0;

	Debugbar::info('row', $rows);

	foreach ($rows as $row) {
		$allocated_fixed = $row->allocated_fixed;
		$allocated_percent = $row->allocated_percent;
		$calculated_allocation = $row->calculated_allocation;

		//so that the total displays '-' instead of $0.00 if there were no values to add up.
		if ($allocated_fixed && $fixed_sum === '-') {
			$fixed_sum = 0;
		}
		
		if ($allocated_fixed) {
			$fixed_sum+= $allocated_fixed;
		}

		$percent_sum+= $allocated_percent;
		Debugbar::info('calculated_allocation: ' . $calculated_allocation);
		$calculated_allocation_sum+= $calculated_allocation;
		Debugbar::info('calculated_allocation_sum: ' . $calculated_allocation_sum);

	}

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
	$total_spent_before_CSD = 0;

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
	    $spent_before_CSD = getTotalSpentOnTagBeforeCSD($tag_id, $CSD);		

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
		$total_spent_before_CSD += $spent_before_CSD;

		$CSD = convertDate($CSD, 'user');

		$budget = number_format($budget, 2);
		$spent = number_format($spent, 2);
		$received = number_format($received, 2);
		$remaining = number_format($remaining, 2);
		$spent_before_CSD = number_format($spent_before_CSD, 2);

		$tag_info = array(
			"id" => $tag_id,
			"name" => $tag_name,
			"budget" => $budget,
			"CSD" => $CSD,
			"CMN" => $CMN,
			"spent" => $spent,
			"received" => $received,
			"remaining" => $remaining,
			"spent_before_CSD" => $spent_before_CSD
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
		"remaining" => $total_remaining,
		"spent_before_CSD" => $total_spent_before_CSD
	);


	if ($type === 'fixed') {
		$budget_info['totals']['cumulative_budget'] = $total_cumulative_budget;
	}

	return $budget_info;
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