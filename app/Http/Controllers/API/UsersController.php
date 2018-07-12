<?php

namespace App\Http\Controllers\API;

use App\Exceptions\NotLoggedInException;
use App\Http\Controllers\Controller;
use App\Http\Transformers\UserTransformer;
use App\Models\Preference;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class UsersController
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, User $user)
    {
        return $this->respondShow($user, new UserTransformer);
    }

    /**
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function showCurrentUser()
    {
        return $this->respondShow(Auth::user(), new UserTransformer);
    }

    /**
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function update(Request $request)
    {
        // Create an array with the new fields merged
//        $data = array_compare($user->toArray(), $request->only([
//            'preferences'
//        ]));
//
//        $user->update($data);

        $user = Auth::user();
        $user->preferences()->merge($request->get('preferences'));

        return response($user, Response::HTTP_OK);
    }

    /**
     *
     * @return mixed
     */
    public function getDateFormat()
    {
        return Preference::forCurrentUser()
            ->where('type', 'date_format')
            ->value('value');
    }

    /**
     * Not sure if I'm still using this
     * @param Request $request
     */
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
        } else {
            //They could actually be logged in, but trying to delete another account mischievously
            throw new NotLoggedInException;
        }
    }

}
