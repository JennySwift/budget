<?php namespace App\Http\Controllers;

use App\Http\Requests;
use JavaScript;

class ChartsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        JavaScript::put([
            'me' => Auth::user()
        ]);

        return view('charts');
    }
}
