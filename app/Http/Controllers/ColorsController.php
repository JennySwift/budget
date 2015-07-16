<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Color;
use Auth;
use DB;
use Illuminate\Http\Request;

/**
 * Class ColorsController
 * @package App\Http\Controllers
 */
class ColorsController extends Controller
{
    /**
     *
     * @return array
     */
    public function getColors()
    {
        return Color::getColors();
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

}
