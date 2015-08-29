<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Color;
use App\Models\Preference;
use Auth;
use Illuminate\Http\Request;

/**
 * Class PreferencesController
 * @package App\Http\Controllers
 */
class PreferencesController extends Controller
{
    /**
     *
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        $user->preferences()->merge($request->all());

        return $user->settings;
    }

    /**
     *
     * @param Request $request
     */
    public function updateColors(Request $request)
    {
        $colors = $request->get('colors');

        foreach ($colors as $type => $color) {
            Color::where('item', $type)
                ->where('user_id', Auth::user()->id)
                ->update([
                    'color' => $color
                ]);
        }
    }

    /**
     *
     * @return mixed
     */
    public function getDateFormat()
    {
        return Preference::where('user_id', Auth::user()->id)
            ->where('type', 'date_format')
            ->pluck('value');
    }

    // public function insertDateFormat($value)
    // {
    // 	Preference::insert([
    // 		'type' => 'date_format',
    // 		'value' => $value,
    // 		'user_id' => Auth::user()->id
    // 	]);
    // }

    /**
     *
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

    // public function updateDateFormat($value)
    // {
    // 	Preference::where('user_id', Auth::user()->id)
    // 		->where('type', 'date_format')
    // 		->update(['value' => $value]);
    // }

}
