<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Models\Transaction;

use Illuminate\Http\Request;

class TransactionsController extends Controller {

	/**
	 * select
	 */
	
	public function countTransactionsWithTag(Request $request)
	{
		$tag_id = $request->get('tag_id');
		$sql = "SELECT COUNT(*) FROM transactions_tags WHERE tag_id = $tag_id";
		$count = DB::table('transactions_tags')->where('tag_id', $tag_id)->count();
		return $count;
	}

	public function autocompleteTransaction(Request $request)
	{
		$typing = $request->get('typing');
		$typing = '%' . $typing . '%';
		$column = $request->get('column');
		$transactions = Transaction::autocompleteTransaction($column, $typing);
		// $transactions = removeNearDuplicates($transactions);
		// $transactions = array_slice($transactions, 0, 50);
		return $transactions;
	}

	public function filter(Request $request)
	{
		include(app_path() . '/inc/functions.php');
		$filter = $request->get('filter');
		return filter($filter);    
	}
	
	/**
	 * insert
	 */
	
	public function insertTransaction(Request $request)
	{
		$new_transaction = $request->get('new_transaction');
		$type = $new_transaction['type'];

		if ($type !== "transfer") {
		    Transaction::insertTransaction($new_transaction, $type);
		}
		else {
		    //It's a transfer, so insert two transactions, the from and the to
		    Transaction::insertTransaction($new_transaction, "from");
		    Transaction::insertTransaction($new_transaction, "to");
		}

		//check if the transaction that was just entered has multiple budgets. Note for transfers this won't do both of them.
		$last_transaction_id = Transaction::getLastTransactionId();
		$transaction = Transaction::getTransaction($last_transaction_id);
		$multiple_budgets = Budget::hasMultipleBudgets($last_transaction_id);

		$array = array(
		    "transaction" => $transaction,
		    "multiple_budgets" => $multiple_budgets
		);
		return $array;
	}

	/**
	 * update
	 */
	
	public function updateMassDescription()
	{
		
	}

	public function updateTransaction(Request $request)
	{
		$transaction = $request->get('transaction');
		Transaction::updateTransaction($transaction);
	}

	public function updateReconciliation(Request $request)
	{
		include(app_path() . '/inc/functions.php');
		$id = $request->get('id');
		$reconciled = $request->get('reconciled');
		$reconciled = convertFromBoolean($reconciled);
		DB::table('transactions')->where('id', $id)->update(['reconciled' => $reconciled]);
	}

	/**
	 * delete
	 */

	public function deleteTransaction(Request $request)
	{
		$transaction_id = $request->get('transaction_id');
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
