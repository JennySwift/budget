<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

use Illuminate\Http\Request;

class InsertController extends Controller {

	//
	public function tag () {
		$new_tag_name = json_decode(file_get_contents('php://input'), true)["new_tag_name"];
		DB::table('tags')->insert(['name' => $new_tag_name, 'user_id' => '1']);
	}
}
