<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TotalsController extends Controller {

	//
	public function ASR () {
		include(app_path() . '/inc/total-functions.php');
		$transactions = json_decode(file_get_contents('php://input'), true)["transactions"];
		return getASR($transactions); 
	}

	public function filterTotals () {
		include(app_path() . '/inc/total-functions.php');
		$transactions = json_decode(file_get_contents('php://input'), true)["transactions"];
		return getFilterTotals($transactions); 
	}

	public function basicTotals () {
		include(app_path() . '/inc/total-functions.php');

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
	}

	public function budgetTotals () {
		// include(app_path() . '/inc/total-functions.php');
		// $FB_info = getBudgetInfo($db, $user_id, 'fixed');
		// $FLB_info = getBudgetInfo($db, $user_id, 'flex');
		
		// $array = array(
		//     "FB" => $FB_info,
		//     "FLB" => $FLB_info
		// );
	}

}
