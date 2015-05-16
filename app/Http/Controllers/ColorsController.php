<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Models\Color;

use Illuminate\Http\Request;

class ColorsController extends Controller {

	/**
	 * select
	 */
	
	public function getColors()
	{
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

	/**
	 * insert
	 */
	
	/**
	 * update
	 */
	
	public function updateColors()
	{
		$colors = json_decode(file_get_contents('php://input'), true)["colors"];
		
		foreach ($colors as $type => $color) {
		    DB::table('colors')->where('item', $type)->where('user_id', Auth::user()->id)->update(['color' => $color]);
		}  
	}

	/**
	 * delete
	 */

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
