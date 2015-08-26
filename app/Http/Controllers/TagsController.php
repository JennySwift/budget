<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Tag;
use App\Repositories\Tags\TagsRepository;
use App\Services\TotalsService;
use Auth;
use DB;
use Illuminate\Http\Request;
use JavaScript;
use Debugbar;

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
     * @var TotalsService
     */
    private $totalsService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TagsRepository $tagsRepository, TotalsService $totalsService)
    {
        $this->middleware('auth');
        $this->tagsRepository = $tagsRepository;
        $this->totalsService = $totalsService;
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
//    public function duplicateTagCheck(Request $request)
//    {
//        $count = Tag::where('name', $request->get('new_tag_name'))
//            ->where('user_id', Auth::user()->id)
//            ->count();
//
//        return $count;
//    }

    /**
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $tag = new Tag(['name' => $request->get('new_tag_name')]);
        $tag->user()->associate(Auth::user());

        checkForDuplicates($tag);

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

        checkForDuplicates($tag);

        $tag->save();
    }

    /**
     * Update the starting date for a tag
     * @param Request $request
     */
    public function update(Request $request)
    {
//        $data = array_compare($exercise->toArray(), $request->get('exercise'));
//        $exercise->update($data);

        $tag = Tag::find($request->get('tag_id'));

        $budget = $request->get('budget');

        if ($request->get('column') === "fixed_budget") {
            $tag->fixed_budget = $budget;
            $tag->budget_id = 1;
        }
        else {
            $tag->flex_budget = $budget;
            $tag->budget_id = 2;
        }

        if ($request->get('starting_date')) {
            $tag->starting_date = $request->get('starting_date');
        }

        $tag->save();

        return [
            'totals' => $this->totalsService->getBasicAndBudgetTotals(),
            'tag' => $tag
        ];
    }

    /**
     * This is done with an update not a delete
     */
    public function removeBudget(Request $request)
    {
        $tag = Tag::find($request->get('tag_id'));
        $tag->fixed_budget = null;
        $tag->flex_budget = null;
        $tag->budget_id = null;
        $tag->starting_date = null;
        $tag->save();

        return [
            'totals' => $this->totalsService->getBasicAndBudgetTotals(),
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
