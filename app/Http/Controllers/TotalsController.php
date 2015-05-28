<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;

/**
 * Models
 */
use App\Models\Budget;
use App\Models\Tag;
use App\Models\Savings;
use App\Models\Transaction;

class TotalsController extends Controller {
	
	public function getAllocationTotals(Request $request)
	{
		$transaction_id = $request->get('transaction_id');
		
		return Budget::getAllocationTotals($transaction_id);
	}

	public function getBasicTotals()
	{
		$total_income = $this->getTotalIncome();
		$total_expense = $this->getTotalExpense();
		$balance = $total_income + $total_expense;
		$reconciled_sum = $this->getReconciledSum();
		$savings_total = $this->getSavingsTotal();
		$savings_balance = $balance - $savings_total;
		$expense_without_budget_total = $this->getTotalExpenseWithoutBudget();
		$EFLB = $this->getTotalExpenseWithFLB();
		
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

	public function getBudgetTotals()
	{
		$user_id = Auth::user()->id;
		$FB_info = $this->getBudgetInfo($user_id, 'fixed');
		$FLB_info = $this->getBudgetInfo($user_id, 'flex');

		$remaining_balance = $this->getRB();

		//adding the calculated budget for each tag. I'm doing it here rather than in getBudgetInfo because $remaining_balance is needed before each calculated_budget can be calculated.
		//I'm doing it like this (creating a new array) because it didn't work when I tried to modify the original array.
		$FLB_tags_with_calculated_budgets = array();
		$total_calculated_budget = 0;
		$total_remaining = 0;

		foreach ($FLB_info['each_tag'] as $tag) {
			$budget = $tag['budget'];
			$spent = $tag['spent'];
			$received = $tag['received'];

			$calculated_budget = $remaining_balance / 100 * $budget;
			$total_calculated_budget+= $calculated_budget;

			$remaining = $calculated_budget + $spent + $received;
			$total_remaining += $remaining;

			$calculated_budget = number_format($calculated_budget, 2);
			$remaining = number_format($remaining, 2);
			$spent = number_format($spent, 2);
			$received = number_format($received, 2);

			$tag['calculated_budget'] = $calculated_budget;
			$tag['remaining'] = $remaining;
			$tag['spent'] = $spent;
			$tag['received'] = $received;

			$FLB_tags_with_calculated_budgets[] = $tag;
		}

		$FLB_info['each_tag'] = $FLB_tags_with_calculated_budgets;

		$total_calculated_budget = number_format($total_calculated_budget, 2);
		$total_remaining = number_format($total_remaining, 2);

		$FLB_info['totals']['calculated_budget'] = $total_calculated_budget;
		$FLB_info['totals']['remaining'] = $total_remaining;
		
		$remaining_balance = number_format($remaining_balance, 2);

		//formatting
		$FB_info['totals'] = $this->numberFormat($FB_info['totals']);
		
		$array = array(
		    "FB" => $FB_info,
		    "FLB" => $FLB_info,
		    "RB" => $remaining_balance
		);
		return $array;
	}

	public function getRB()
	{
		$user_id = Auth::user()->id;
		$FB_info = $this->getBudgetInfo($user_id, 'fixed');
		$FLB_info = $this->getBudgetInfo($user_id, 'flex');

		//calculating remaining balance
		$total_income = $this->getTotalIncome();
		$total_CFB = $FB_info['totals']['cumulative_budget'];
		$EWB = $this->getTotalExpenseWithoutBudget();
		$EFLB = $this->getTotalExpenseWithFLB();
		$total_spent_before_CSD = $FB_info['totals']['spent_before_CSD'];
		$total_spent_after_CSD = $FB_info['totals']['spent'];
		$total_savings = $this->getSavingsTotal();

		$RB = $total_income - $total_CFB + $EWB + $EFLB + $total_spent_before_CSD + $total_spent_after_CSD - $total_savings;
		// dd($total_income - $total_CFB + $EWB + $EFLB);
		return $RB;
	}

	public function getTotalExpenseWithFLB()
	{
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

		$total = DB::table('transactions_tags')
			->whereIn('transaction_id', $ids)
			->where('budget_id', 2)
			->join('tags', 'tag_id', '=', 'tags.id')
			->sum('calculated_allocation');

		// dd($ids);
		// dd('total: ' . $total);
		return $total;
	}

	public function getTotalExpenseWithoutBudget()
	{
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


	public function getSavingsTotal()
	{
		return Savings::getSavingsTotal();
	}

	public function getTotalSpentOnTagBeforeCSD($tag_id, $CSD)
	{
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

		return $total;
	}

	public function getTotalSpentOnTag($tag_id, $starting_date)
	{
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

		return $total;
	}

	public function getTotalReceivedOnTag($tag_id, $starting_date)
	{
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

	public function getTotalIncomeAfterDate($db, $user_id, $cumulative_starting_date)
	{
		//gets the total income after the cumulative starting date
		$total_income = DB::table('transactions')
			->where('type', 'income')
			->where('transactions.date', '>=', $cumulative_starting_date)
			->where('user_id', Auth::user()->id)
			->sum('transactions.total');
		
		return $total_income;
	}

	public function getReconciledSum()
	{
		//gets the sum of all transactions that are reconciled
		$reconciled_sum = DB::table('transactions')
			->where('reconciled', 1)
			->where('user_id', Auth::user()->id)
			->sum('total');

		return $reconciled_sum;
	}

	public function getTotalIncome()
	{
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

	public function getTotalExpense()
	{
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

	public function getBudgetInfo($user_id, $type)
	{
		if ($type === 'fixed') {
			$tags = Tag::getTagsWithFixedBudget($user_id);
		}
		elseif ($type === 'flex') {
			$tags = Tag::getTagsWithFlexBudget($user_id);
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

			if ($CSD) {
				$CMN = Budget::getCMN($CSD);
			}
			
			else {
				$CMN = 1;
			}

			if ($type === 'fixed') {
				$cumulative_budget = $budget * $CMN;
			}
			
		    $spent = $this->getTotalSpentOnTag($tag_id, $CSD);
		    $received = $this->getTotalReceivedOnTag($tag_id, $CSD);
		    $spent_before_CSD = $this->getTotalSpentOnTagBeforeCSD($tag_id, $CSD);		

			$total_budget += $budget;

			if ($type === 'fixed') {
				$remaining = $cumulative_budget + $spent + $received;
				$total_remaining += $remaining;
				$total_cumulative_budget += $cumulative_budget;
			}
			elseif ($type === 'flex') {
				
			}
			
			$total_spent += $spent;
			$total_received += $received;	
			$total_spent_before_CSD += $spent_before_CSD;

			if ($CSD) {
				$CSD = Transaction::convertDate($CSD, 'user');
			}
			
			$budget = number_format($budget, 2);
			$spent_before_CSD = number_format($spent_before_CSD, 2);

			$tag_info = array(
				"id" => $tag_id,
				"name" => $tag_name,
				"budget" => $budget,
				"CSD" => $CSD,
				"CMN" => $CMN,
				"spent" => $spent,
				"received" => $received,
				"spent_before_CSD" => $spent_before_CSD
			);

			if ($type === 'fixed') {
				$cumulative_budget = number_format($cumulative_budget, 2);
				$tag_info['cumulative_budget'] = $cumulative_budget;

				$remaining = number_format($remaining, 2);
				$tag_info['remaining'] = $remaining;
			}

			$budget_info['each_tag'][] = $tag_info;
		}

		$budget_info['totals'] = array(
			"budget" => $total_budget,
			"spent" => $total_spent,
			"received" => $total_received,
			"spent_before_CSD" => $total_spent_before_CSD
		);


		if ($type === 'fixed') {
			$budget_info['totals']['cumulative_budget'] = $total_cumulative_budget;
			$budget_info['totals']['remaining'] = $total_remaining;
		}

		return $budget_info;
	}

	public function numberFormat($array)
	{
		$formatted_array = array();
		foreach ($array as $key => $value) {
			$formatted_value = number_format($value, 2);
			$formatted_array[$key] = $formatted_value;
		}

		return $formatted_array;
	}

}
