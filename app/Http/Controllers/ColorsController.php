<?php namespace App\Http\Controllers;

use App\Http\Requests;
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
        $user_id = Auth::user()->id;
        $income = DB::table('colors')->where('item', 'income')->where('user_id', $user_id)->pluck('color');
        $expense = DB::table('colors')->where('item', 'expense')->where('user_id', $user_id)->pluck('color');
        $transfer = DB::table('colors')->where('item', 'transfer')->where('user_id', $user_id)->pluck('color');

        $colors = array(
            "income" => $income,
            "expense" => $expense,
            "transfer" => $transfer
        );

        return $colors;
    }

    /**
     *
     * @param Request $request
     */
    public function updateColors(Request $request)
    {
        $colors = $request->get('colors');

        foreach ($colors as $type => $color) {
            DB::table('colors')->where('item', $type)->where('user_id', Auth::user()->id)->update(['color' => $color]);
        }
    }

}
