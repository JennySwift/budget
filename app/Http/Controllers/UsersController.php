<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller {

	/**
	 * For debugging.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $dummy = User::whereEmail('cheezyspaghetti@gmail.com')->first();
        $dummy->delete();
	}

}
