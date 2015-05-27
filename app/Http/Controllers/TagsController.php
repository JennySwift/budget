<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Models\Tag;

use Illuminate\Http\Request;

class TagsController extends Controller {

	/**
	 * select
	 */
	
	public function getTags()
	{
		$sql = "SELECT * FROM tags WHERE user_id = " . Auth::user()->id . " ORDER BY name ASC";
		$tags = DB::select($sql);
		return $tags;
	}

	public function duplicateTagCheck(Request $request)
	{
		$new_tag_name = $request->get('new_tag_name');
		$count = DB::table('tags')->where('name', $new_tag_name)->where('user_id', Auth::user()->id)->count();
		//count is 0 if tag is not a duplicate, 1 if it is.
		return $count;
	}

	/**
	 * insert
	 */
	
	public function insertTag(Request $request)
	{
		$new_tag_name = $request->get('new_tag_name');
		DB::table('tags')->insert(['name' => $new_tag_name, 'user_id' => Auth::user()->id]);
	}

	/**
	 * update
	 */
	
	public function updateTagName(Request $request)
	{
		$tag_id = $request->get('tag_id');
		$tag_name = $request->get('tag_name');
		DB::table('tags')->where('id', $tag_id)->update(['name' => $tag_name]);
	}

	public function updateMassTags()
	{
		
	}

	/**
	 * delete
	 */

	public function deleteTag(Request $request)
	{
		$tag_id = $request->get('tag_id');
		DB::table('tags')->where('id', $tag_id)->delete();
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
