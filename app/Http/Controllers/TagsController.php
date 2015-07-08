<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Tag;
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
     * Get all the tags that belong to the user
     * @return mixed
     */
    public function getTags()
    {
        $tags = Tag::where('user_id', Auth::user()->id)
            ->orderBy('name', 'asc')
            ->get();

        return $tags;
    }

    /**
     * Check if the tag already exists for the user.
     * $count is 0 if tag is not a duplicate, 1 if it is.
     * @param Request $request
     * @return mixed
     */
    public function duplicateTagCheck(Request $request)
    {
        $count = Tag::where('name', $request->get('new_tag_name'))
            ->where('user_id', Auth::user()->id)
            ->count();

        return $count;
    }

    /**
     *
     * @param Request $request
     */
    public function insertTag(Request $request)
    {
        $tag = new Tag(['name' => $request->get('new_tag_name')]);
        $tag->user()->associate(Auth::user());
        $tag->save();
    }

    /**
     *
     * @param Request $request
     */
    public function updateTagName(Request $request)
    {
        $tag = Tag::find($request->get('tag_id'));
        $tag->name = $request->get('tag_name');
        $tag->save();
    }

    /**
     *
     */
    public function updateMassTags()
    {

    }

    /**
     * This either adds or deletes a budget, both using an update query.
     * @param Request $request
     */
    public function updateBudget(Request $request)
    {
        $tag = Tag::find($request->get('tag_id'));

        $budget = $request->get('budget');
        $column = $request->get('column');

        if (!$budget || $budget === "NULL") {
            $budget = null;
            $budget_id = null;
        }
        else {
            if ($column === "fixed_budget") {
                $budget_id = 1;
            } else {
                $budget_id = 2;
            }
        }

        Tag::where('id', $tag->id)
            ->update([
                $column => $budget,
                'budget_id' => $budget_id
            ]);
    }

    /**
     *
     * @param Request $request
     */
    public function updateCSD(Request $request)
    {
        $tag = Tag::find($request->get('tag_id'));
        $tag->starting_date = $request->get('CSD');
        $tag->save();
    }

    /**
     *
     * @param Request $request
     */
    public function deleteTag(Request $request)
    {
        $tag = Tag::find($request->get('tag_id'));
        $tag->delete();
    }
}
