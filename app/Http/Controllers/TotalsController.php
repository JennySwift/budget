<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

class TotalsController extends Controller {
	
	public function getAllocationTotals()
	{
		include(app_path() . '/inc/functions.php');
		$transaction_id = json_decode(file_get_contents('php://input'), true)["transaction_id"];
		return getAllocationTotals($transaction_id);
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
