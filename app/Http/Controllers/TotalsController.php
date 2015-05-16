<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\Budget;

class TotalsController extends Controller {
	
	public function getAllocationTotals(Request $request)
	{
		$transaction_id = $request->get('transaction_id');
		return Budget::getAllocationTotals($transaction_id);
	}

	public function basic()
	{
		include(app_path() . '/inc/functions.php');
		return getBasicTotals();
	}

	public function budget()
	{
		include(app_path() . '/inc/functions.php');
		return getBudgetTotals();
	}

}
