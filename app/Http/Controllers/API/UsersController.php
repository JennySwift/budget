<?php

namespace App\Http\Controllers\API;

use App\Exceptions\NotLoggedInException;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User;
use Auth;
use Debugbar;

/**
 * Class UsersController
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{

    /**
     * Delete the user's account
     * DELETE api/user/{user}
     * @param $id
     */
    public function destroy($id)
    {
        if ($id == Auth::user()->id) {
            $user = User::findOrFail($id);
            $user->delete();
            Auth::logout();
        }
        else {
            //They could actually be logged in, but trying to delete another account mischievously
            throw new NotLoggedInException;
        }
    }

}
