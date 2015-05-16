<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class BudgetsController extends Controller {

	/**
	 * select
	 */
	
	public function getAllocationInfo () {
		// include(app_path() . '/inc/functions.php');
		// $filter = json_decode(file_get_contents('php://input'), true)["filter"];
		// return filter($filter);    
	}

	/**
	 * insert
	 */
	
	/**
	 * update
	 */
	
	public function updateBudget () {
		//this either adds or deletes a budget, both using an update query.
		include(app_path() . '/inc/functions.php');
		$tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
		$budget = json_decode(file_get_contents('php://input'), true)["budget"];
		$column = json_decode(file_get_contents('php://input'), true)["column"];

		updateBudget($tag_id, $budget, $column);
	}

	public function updateAllocation () {
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

	public function updateAllocationStatus () {
		include(app_path() . '/inc/functions.php');
		$transaction_id = json_decode(file_get_contents('php://input'), true)["transaction_id"];
		$status = json_decode(file_get_contents('php://input'), true)["status"];
		
		updateAllocationStatus($transaction_id, $status);
	}

	public function updateStartingDate () {
		
	}

	public function updateCSD () {
		$tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
		$CSD = json_decode(file_get_contents('php://input'), true)["CSD"];

		DB::table('tags')->where('id', $tag_id)->update(['starting_date' => $CSD]);
	}

	/**
	 * delete
	 */

	public function deleteBudget () {
		
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
