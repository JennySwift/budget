<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

use Illuminate\Http\Request;

class SelectController extends Controller {

	//
	public function filter () {
		include(app_path() . '/inc/functions.php');
		$filter = json_decode(file_get_contents('php://input'), true)["filter"];
		return filter($filter);    
	}

	public function colors () {
		$income = DB::table('colors')->where('item', 'income')->where('user_id', '1')->pluck('color');
		$expense = DB::table('colors')->where('item', 'expense')->where('user_id', '1')->pluck('color');
		$transfer = DB::table('colors')->where('item', 'transfer')->where('user_id', '1')->pluck('color');
	
		$colors = array(
			"income" => $income,
			"expense" => $expense,
			"transfer" => $transfer
		);
		return $colors;
	}

	public function tags () {
		$sql = "SELECT * FROM tags WHERE user_id = 1 ORDER BY name ASC";
		$tags = DB::select($sql);
		return $tags;
	}

	public function duplicateTagCheck () {
		$new_tag_name = json_decode(file_get_contents('php://input'), true)["new_tag_name"];
		$count = DB::table('tags')->where('name', $new_tag_name)->where('user_id', '1')->count();
		//count is 0 if tag is not a duplicate, 1 if it is.
		return $count;
	}

}
