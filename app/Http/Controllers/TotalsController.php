<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\Budget;

class TotalsController extends Controller {
	
	public function getAllocationTotals()
	{
		$transaction_id = json_decode(file_get_contents('php://input'), true)["transaction_id"];
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
