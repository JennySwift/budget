<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;

/**
 * Class SettingsController
 * @package App\Http\Controllers
 */
class SettingsController extends Controller
{

    /**
     *
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        $user->settings()->merge($request->all());
        return $user->settings;
    }

}
