<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;

use Illuminate\Http\Request;

class UpdateController extends Controller {

	//
	public function budget () {
		//this either adds or deletes a budget, both using an update query.
		include(app_path() . '/inc/functions.php');
		$tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
		$budget = json_decode(file_get_contents('php://input'), true)["budget"];
		$column = json_decode(file_get_contents('php://input'), true)["column"];

		updateBudget($tag_id, $budget, $column);
	}

	public function tagName () {
		$tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
		$tag_name = json_decode(file_get_contents('php://input'), true)["tag_name"];
		DB::table('tags')->where('id', $tag_id)->update(['name' => $tag_name]);
	}

	public function accountName () {
		$account_id = json_decode(file_get_contents('php://input'), true)["account_id"];
		$account_name = json_decode(file_get_contents('php://input'), true)["account_name"];
		DB::table('accounts')->where('id', $account_id)->update(['name' => $account_name]);
	}

	public function allocation () {
		include(app_path() . '/inc/functions.php');
		$type = json_decode(file_get_contents('php://input'), true)["type"];
		$value = json_decode(file_get_contents('php://input'), true)["value"];
		$transaction_id = json_decode(file_get_contents('php://input'), true)["transaction_id"];
		$tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];

		if ($type === 'percent') {
		    updateAllocatedPercent($value, $transaction_id, $tag_id);
		}
		elseif ($type === 'fixed') {
		    updateAllocatedFixed($value, $transaction_id, $tag_id);
		}
		
		//get the updated tag info after the update
		$allocation_info = getAllocationInfo($transaction_id, $tag_id);
		$allocation_totals = getAllocationTotals($transaction_id);

		$array = array(
		    "allocation_info" => $allocation_info,
		    "allocation_totals" => $allocation_totals
		);
		return $array;
	}

	public function allocationStatus () {
		include(app_path() . '/inc/functions.php');
		$transaction_id = json_decode(file_get_contents('php://input'), true)["transaction_id"];
		$status = json_decode(file_get_contents('php://input'), true)["status"];
		
		updateAllocationStatus($transaction_id, $status);
	}

	public function massTags () {
		
	}

	public function massDescription () {
		
	}

	public function startingDate () {
		
	}

	public function CSD () {
		$tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
		$CSD = json_decode(file_get_contents('php://input'), true)["CSD"];

		DB::table('tags')->where('id', $tag_id)->update(['starting_date' => $CSD]);
	}

	public function colors () {
		$colors = json_decode(file_get_contents('php://input'), true)["colors"];
		
		foreach ($colors as $type => $color) {
		    DB::table('colors')->where('item', $type)->where('user_id', Auth::user()->id)->update(['color' => $color]);
		}  
	}

	public function transaction () {
		include(app_path() . '/inc/functions.php');
		$transaction = json_decode(file_get_contents('php://input'), true)["transaction"];

		$transaction_id = $transaction['id'];
		$account_id = $transaction['account']['id'];
		$date = $transaction['date']['sql'];
		$merchant = $transaction['merchant'];
		$total = $transaction['total'];
		$tags = $transaction['tags'];
		$description = $transaction['description'];
		$type = $transaction['type'];
		$reconciliation = $transaction['reconciled'];
		$reconciliation = convertFromBoolean($reconciliation);

		DB::table('transactions')
			->where('id', $transaction_id)
			->update([
				'account_id' => $account_id,
				'type' => $type,
				'date' => $date,
				'merchant' => $merchant,
				'total' => $total,
				'description' => $description,
				'reconciled' => $reconciliation
			]);

		//delete all previous tags for the transaction and then add the current ones 
		deleteAllTagsForTransaction($transaction_id);

		insertTags($transaction_id, $tags);
	}

	public function reconciliation () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		$reconciled = json_decode(file_get_contents('php://input'), true)["reconciled"];
		$reconciled = convertFromBoolean($reconciled);
		DB::table('transactions')->where('id', $id)->update(['reconciled' => $reconciled]);
	}
}
