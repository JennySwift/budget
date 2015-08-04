<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Tag;
use App\Repositories\Tags\TagsRepository;
use App\Totals\FixedAndFlexData;
use App\Totals\TotalsService;
use Auth;
use DB;
use Illuminate\Http\Request;
use JavaScript;

/**
 * Class TagsController
 * @package App\Http\Controllers
 */
class TagsController extends Controller
{
    /**
     * @var TagsRepository
     */
    protected $tagsRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TagsRepository $tagsRepository)
    {
        $this->middleware('auth');
        $this->tagsRepository = $tagsRepository;
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        JavaScript::put([
            'me' => Auth::user()
        ]);

        return view('tags');
    }

    /**
     * Get all the tags that belong to the user
     * @return mixed
     */
    public function getTags()
    {
        return $this->tagsRepository->getTags();
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
     * Update the starting date for a tag
     * @param Request $request
     */
    public function update(Request $request)
    {
        $total = new Total();
        $tag = Tag::find($request->get('tag')['id']);
        $tag->starting_date = $request->get('CSD');
        $tag->save();

        return $total->getBasicAndBudgetTotals();
    }

    /**
     * This either adds or deletes a budget, both using an update query.
     * Todo: Combine this method into the update method
     * @param Request $request
     */
    public function updateBudget(Request $request)
    {
        $tag = Tag::find($request->get('tag_id'));
        $fixedAndFlexData = new FixedAndFlexData();
        $totalsService = new TotalsService($fixedAndFlexData);

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

        $tag->update([
            $column => $budget,
            'budget_id' => $budget_id
        ]);

        return [
            'totals' => $totalsService->getBasicAndBudgetTotals(),
            'tag' => $tag
        ];
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
        $tag = Tag::find($request->get('tag_id'));
        $tag->delete();
    }
}
