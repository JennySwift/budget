<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Auth;
use DB;
use Illuminate\Http\Request;

/**
 * Class TagsController
 * @package App\Http\Controllers
 */
class TagsController extends Controller
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
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        return view('tags');
    }

    /**
     *
     * @return mixed
     */
    public function getTags()
    {
        $sql = "SELECT * FROM tags WHERE user_id = " . Auth::user()->id . " ORDER BY name ASC";
        $tags = DB::select($sql);

        return $tags;
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function duplicateTagCheck(Request $request)
    {
        $new_tag_name = $request->get('new_tag_name');
        $count = DB::table('tags')->where('name', $new_tag_name)->where('user_id', Auth::user()->id)->count();

        //count is 0 if tag is not a duplicate, 1 if it is.
        return $count;
    }

    /**
     *
     * @param Request $request
     */
    public function insertTag(Request $request)
    {
        $new_tag_name = $request->get('new_tag_name');
        DB::table('tags')->insert(['name' => $new_tag_name, 'user_id' => Auth::user()->id]);
    }

    /**
     *
     * @param Request $request
     */
    public function updateTagName(Request $request)
    {
        $tag_id = $request->get('tag_id');
        $tag_name = $request->get('tag_name');
        DB::table('tags')->where('id', $tag_id)->update(['name' => $tag_name]);
    }

    /**
     *
     */
    public function updateMassTags()
    {

    }

    /**
     *
     * @param Request $request
     */
    public function deleteTag(Request $request)
    {
        $tag_id = $request->get('tag_id');
        DB::table('tags')->where('id', $tag_id)->delete();
    }
}
