<?php namespace App\Repositories\Tags;

use App\Models\Tag;
use App\Services\BudgetService;
use App\Services\BudgetTableTotalsService;
use App\Services\TotalsService;
use Auth;

/**
 * Class TagsRepository
 * @package App\Repositories\Tags
 */
class TagsRepository {
    /**
     * @var
     */
    protected $budgetService;

//    This errored again.
    /**
     * @param BudgetService $budgetService
     */
//    public function __construct(BudgetService $budgetService) {
//        $this->budgetService = $budgetService;
//    }

    /**
     *
     * @param $user_id
     * @return mixed
     */
    public function getTagsWithFixedBudget()
    {
        $tags = Tag::where('user_id', Auth::user()->id)
            ->where('flex_budget', null)
            ->whereNotNull('fixed_budget')
            ->orderBy('name', 'asc')
            ->get();

        return $tags;
    }

    /**
     *
     * @param $user_id
     * @return mixed
     */
    public function getTagsWithFlexBudget()
    {
        $tags = Tag::where('user_id', Auth::user()->id)
            ->whereNotNull('flex_budget')
            ->orderBy('name', 'asc')
            ->get();

//        foreach ($tags as $tag) {
//            $tag->getCalculatedBudget();
//        }

        return $tags;
    }

    /**
     *
     * @return mixed
     */
    public function getTags()
    {
        return Tag::where('user_id', Auth::user()->id)
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     *
     * @param $type
     * @return mixed
     */
    public function getTagsWithSpecifiedBudget($type)
    {
        if ($type === 'fixed') {
            $tags = $this->getTagsWithFixedBudget();
        }
        elseif ($type === 'flex') {
            $tags = $this->getTagsWithFlexBudget();
        }
        return $tags;
    }
}