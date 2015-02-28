<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;

use Illuminate\Http\Request;

class DeleteController extends Controller {

	//
	public function tag () {
		$tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
		DB::table('tags')->where('id', $tag_id)->delete();
		return 'hello'; 
	}

	public function account () {
		$account_id = json_decode(file_get_contents('php://input'), true)["account_id"];
		DB::table('accounts')->where('id', $account_id)->delete();
	}

	public function item () {
		  
	}

	public function budget () {
		
	}

	public function transaction () {
		$transaction_id = json_decode(file_get_contents('php://input'), true)["transaction_id"];
		DB::table('transactions_tags')->where('transaction_id', $transaction_id)->delete();
		DB::table('transactions')->where('id', $transaction_id)->delete();
	}
}
