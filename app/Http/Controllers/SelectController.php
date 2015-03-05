<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Log;

use Illuminate\Http\Request;

class SelectController extends Controller {

	//
	public function filter () {
		include(app_path() . '/inc/functions.php');
		$filter = json_decode(file_get_contents('php://input'), true)["filter"];
		return filter($filter);    
	}

	public function accounts () {
		$user_id = Auth::user()->id;
		return DB::table('accounts')->where('user_id', $user_id)->orderBy('name', 'asc')->get();
	}

	public function colors () {
		$user_id = Auth::user()->id;
		$income = DB::table('colors')->where('item', 'income')->where('user_id', $user_id)->pluck('color');
		$expense = DB::table('colors')->where('item', 'expense')->where('user_id', $user_id)->pluck('color');
		$transfer = DB::table('colors')->where('item', 'transfer')->where('user_id', $user_id)->pluck('color');
	
		$colors = array(
			"income" => $income,
			"expense" => $expense,
			"transfer" => $transfer
		);
		return $colors;
	}

	public function tags () {
		$sql = "SELECT * FROM tags WHERE user_id = " . Auth::user()->id . " ORDER BY name ASC";
		$tags = DB::select($sql);
		return $tags;
	}

	public function duplicateTagCheck () {
		$new_tag_name = json_decode(file_get_contents('php://input'), true)["new_tag_name"];
		$count = DB::table('tags')->where('name', $new_tag_name)->where('user_id', Auth::user()->id)->count();
		//count is 0 if tag is not a duplicate, 1 if it is.
		return $count;
	}

	public function countTransactionsWithTag () {
		$tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
		$sql = "SELECT COUNT(*) FROM transactions_tags WHERE tag_id = $tag_id";
		$count = DB::table('transactions_tags')->where('tag_id', $tag_id)->count();
		return $count;
	}

	public function autocompleteTransaction () {
		include(app_path() . '/inc/functions.php');
		$typing = json_decode(file_get_contents('php://input'), true)["typing"];
		$typing = '%' . $typing . '%';
		$column = json_decode(file_get_contents('php://input'), true)["column"];
		$transactions = autocompleteTransaction($column, $typing);
		// $transactions = removeNearDuplicates($transactions);
		$transactions = array_slice($transactions, 0, 50);
		return $transactions;
	}

	public function allocationInfo () {
		// include(app_path() . '/inc/functions.php');
		// $filter = json_decode(file_get_contents('php://input'), true)["filter"];
		// return filter($filter);    
	}

	// public function allocationPopup () {
	// 	$transaction_id = json_decode(file_get_contents('php://input'), true)["transaction_id"];
	// 	$array = getAllocationTotals($db, $transaction_id);
	// }

	// public function budgetTags () {
	//I don't think this function is being used?
	// 	// $id_array = json_decode(file_get_contents('php://input'), true)["id_array"];
	// 	$id_array = json_decode(stripslashes($_POST['id_array']));
	// 	$budget_tags = array();

	// 	foreach ($id_array as $id) {
	// 	    $tags_for_one_transaction = array();
		    
	// 	    $sql = "SELECT transactions_tags.transaction_id, transactions_tags.tag_id, tags.name, tags.fixed_budget, tags.flex_budget FROM transactions_tags JOIN tags ON transactions_tags.tag_id = tags.id WHERE transaction_id = '$id';";

	// 	    $result = $db->query($sql);

	// 	    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	// 	        $tag_name = $row['name'];
	// 	        $tags_for_one_transaction[$tag_name] = array(
	// 	            "transaction_id" => $row['transaction_id'],
	// 	            "id" => $row['tag_id'],
	// 	            "name" => $row['name'],
	// 	            "budget" => $row['fixed_budget'],
	// 	            "flex_budget" => $row['flex_budget']
	// 	        );
	// 	    }

	// 	    $budget_tags[$id] = $tags_for_one_transaction;
	// 	}
	// }

}
