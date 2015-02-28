<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;

use Illuminate\Http\Request;

class InsertController extends Controller {

	//
	public function tag () {
		$new_tag_name = json_decode(file_get_contents('php://input'), true)["new_tag_name"];
		DB::table('tags')->insert(['name' => $new_tag_name, 'user_id' => Auth::user()->id]);
	}

	public function account () {
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		DB::table('accounts')->insert(['name' => $name, 'user_id' => Auth::user()->id]);
	}

	// public function flexBudget () {
	// 	$new_tag_name = json_decode(file_get_contents('php://input'), true)["new_tag_name"];
	// 	DB::table('tags')->insert(['name' => $new_tag_name, 'user_id' => '1']);
	// }

	// public function budgetInfo () {
	// 	$new_tag_name = json_decode(file_get_contents('php://input'), true)["new_tag_name"];
	// 	DB::table('tags')->insert(['name' => $new_tag_name, 'user_id' => '1']);
	// }

	public function transaction () {
		include(app_path() . '/inc/functions.php');
		$new_transaction = json_decode(file_get_contents('php://input'), true)["new_transaction"];
		$type = $new_transaction['type'];

		if ($type !== "transfer") {
		    insertTransaction($new_transaction, $type);
		}
		else {
		    //It's a transfer, so insert two transactions, the from and the to
		    insertTransaction($new_transaction, "from");
		    insertTransaction($new_transaction, "to");
		}

		//check if the transaction that was just entered has multiple budgets. Note for transfers this won't do both of them.
		$last_transaction_id = getLastTransactionId();
		$transaction = getTransaction($last_transaction_id);
		$multiple_budgets = hasMultipleBudgets($last_transaction_id);

		$array = array(
		    "transaction" => $transaction,
		    "multiple_budgets" => $multiple_budgets
		); 
	}
}
