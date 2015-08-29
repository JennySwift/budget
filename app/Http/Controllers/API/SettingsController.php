<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Auth;
use Debugbar;
use Illuminate\Http\Request;

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
