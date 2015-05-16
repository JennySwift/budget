<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TagsController extends Controller {

	/**
	 * select
	 */
	
	/**
	 * insert
	 */
	
	public function insertTag () {
		$new_tag_name = json_decode(file_get_contents('php://input'), true)["new_tag_name"];
		DB::table('tags')->insert(['name' => $new_tag_name, 'user_id' => Auth::user()->id]);
	}

	/**
	 * update
	 */
	
	public function updateTagName () {
		$tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
		$tag_name = json_decode(file_get_contents('php://input'), true)["tag_name"];
		DB::table('tags')->where('id', $tag_id)->update(['name' => $tag_name]);
	}

	public function updateMassTags () {
		
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