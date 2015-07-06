<?php namespace App\Http\Controllers;

use App\Http\Requests;
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
     * @return mixed
     */
    public function getDateFormat()
    {
        return Preference::where('user_id', Auth::user()->id)->where('type', 'date_format')->pluck('value');
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
