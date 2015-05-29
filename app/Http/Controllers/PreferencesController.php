<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

use Illuminate\Http\Request;

use App\Models\Preference;

class PreferencesController extends Controller {

	/**
	 * select
	 */
	
	public function getDateFormat()
	{
		return Preference::where('user_id', Auth::user()->id)->where('type', 'date_format')->pluck('value');
	}

	/**
	 * insert
	 */
	
	// public function insertDateFormat($value)
	// {
	// 	Preference::insert([
	// 		'type' => 'date_format',
	// 		'value' => $value,
	// 		'user_id' => Auth::user()->id
	// 	]);
	// }

	public function insertOrUpdateDateFormat(Request $request)
	{
		$new_format = $request->get('new_format');

		$preference = Preference::firstOrNew([
			'type' => 'date_format',
			'user_id' => Auth::user()->id
		]);

		$preference->value = $new_format;
		$preference->user()->associate(Auth::user());
		$preference->save();				
	}

	/**
	 * update
	 */
	
	// public function updateDateFormat($value)
	// {
	// 	Preference::where('user_id', Auth::user()->id)
	// 		->where('type', 'date_format')
	// 		->update(['value' => $value]);
	// }

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
