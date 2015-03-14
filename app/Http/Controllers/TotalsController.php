<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

class TotalsController extends Controller {
	
	public function basic () {
		include(app_path() . '/inc/functions.php');
		return getBasicTotals();
	}

	public function budget () {
		include(app_path() . '/inc/functions.php');
		return getBudgetTotals();
	}

}
