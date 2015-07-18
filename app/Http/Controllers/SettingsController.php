<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use Debugbar;

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
//        Debugbar::info('request', $request);
        $user = Auth::user();
        $user->settings()->merge($request->all());
        return $user->settings;
    }

}
