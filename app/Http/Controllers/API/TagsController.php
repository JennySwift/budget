<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Tags\InsertTagRequest;
use App\Http\Requests\Tags\UpdateTagNameRequest;
use App\Models\Tag;
use App\Repositories\Tags\TagsRepository;
use App\Services\TotalsService;
use Auth;
use DB;
use Debugbar;
use Illuminate\Contracts\Support\Arrayable;
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
     * @var TotalsService
     */
    protected $totalsService;

    /**
     * Create a new controller instance.
     * @param TagsRepository $tagsRepository
     * @param TotalsService $totalsService
     */
    public function __construct(TagsRepository $tagsRepository, TotalsService $totalsService)
    {
        $this->tagsRepository = $tagsRepository;
        $this->totalsService = $totalsService;

        $this->middleware('auth');
    }

    /**
     * Get all the tags that belong to the user
     * @return mixed
     */
    public function index()
    {
        return $this->tagsRepository->getTags();
    }

    /**
     * Show a tag
     * @param Tag $tags
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tags)
    {
        return $this->responseOk($tags);
    }

    /**
     * Insert a tag
     * @param InsertTagRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(InsertTagRequest $request)
    {
        $tag = new Tag($request->only('name'));
        $tag->user()->associate(Auth::user());

        $tag->save();

        return $this->responseCreated($tag);
    }

    /**
     * Update a tag
     * @param UpdateTagNameRequest $request
     * @param Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function updateTagName(UpdateTagNameRequest $request, Tag $tag)
    {
        $tag->name = $request->get('name');

        $tag->save();

        return $this->responseOk($tag);
    }

    /**
     * Update the starting date for a tag
     * @TODO Needs refactoring!!!!
     * @param Request $request
     * @param Tag $tag
     * @return array
     */
    public function update(Request $request, Tag $tag)
    {
//        $data = array_compare($exercise->toArray(), $request->get('exercise'));
//        $exercise->update($data);

        $budget = $request->get('budget');

        if ($request->get('column') === "fixed_budget") {
            $tag->fixed_budget = $budget;
            $tag->budget_id = 1;
        } else {
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
     * Delete a tag
     * @param Tag $tag
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response([], 204);
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
}
