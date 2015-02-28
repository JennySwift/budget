<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

class TotalsController extends Controller {

	//
	public function ASR () {
		include(app_path() . '/inc/functions.php');
		$transactions = json_decode(file_get_contents('php://input'), true)["transactions"];
		return getASR($transactions); 
	}

	public function filter () {
		include(app_path() . '/inc/functions.php');
		$transactions = json_decode(file_get_contents('php://input'), true)["transactions"];
		return getFilterTotals($transactions); 
	}

	public function basic () {
		include(app_path() . '/inc/functions.php');

		$total_income = getTotalIncome();
		$total_expense = getTotalExpense();
		$balance = $total_income + $total_expense;
		$reconciled_sum = getReconciledSum();
		
		$total_income = number_format($total_income, 2);
		$total_expense = number_format($total_expense, 2);
		$balance = number_format($balance, 2);
		$reconciled_sum = number_format($reconciled_sum, 2);

		$totals = array(
		    "total_income" => $total_income,
		    "total_expense" => $total_expense,
		    "balance" => $balance,
		    "reconciled_sum" => $reconciled_sum
		);
		return $totals;
		return $total_income;
	}

	public function budget () {
		$user_id = Auth::user()->id;
		include(app_path() . '/inc/functions.php');
		$FB_info = getBudgetInfo($user_id, 'fixed');
		$FLB_info = getBudgetInfo($user_id, 'flex');
		
		$array = array(
		    "FB" => $FB_info,
		    "FLB" => $FLB_info
		);
		return $array;
	}

}
