<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TransactionsController extends Controller {

	/**
	 * select
	 */
	
	/**
	 * insert
	 */
	
	public function insertTransaction () {
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
		return $array;
	}

	/**
	 * update
	 */
	
	public function updateMassDescription () {
		
	}

	public function updateTransaction () {
		include(app_path() . '/inc/functions.php');
		$transaction = json_decode(file_get_contents('php://input'), true)["transaction"];

		updateTransaction($transaction);
	}

	public function updateReconciliation () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		$reconciled = json_decode(file_get_contents('php://input'), true)["reconciled"];
		$reconciled = convertFromBoolean($reconciled);
		DB::table('transactions')->where('id', $id)->update(['reconciled' => $reconciled]);
	}

	/**
	 * delete
	 */

	public function deleteTransaction () {
		$transaction_id = json_decode(file_get_contents('php://input'), true)["transaction_id"];
		//no need for this now that I'm using cascade.
		// DB::table('transactions_tags')->where('transaction_id', $transaction_id)->delete();
		DB::table('transactions')->where('id', $transaction_id)->delete();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
