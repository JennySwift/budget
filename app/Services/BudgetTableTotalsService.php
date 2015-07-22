<?php

namespace App\Services;


use App\Repositories\Tags\TagsRepository;

/**
 * Class BudgetTableTotalsService
 * For dealing with the totals for the budget tables
 * @package App\Services
 */
class BudgetTableTotalsService {

    /**
     * @var TagsRepository
     */
    protected $tagsRepository;

    /**
     * @param TagsRepository $tagsRepository
     */
    public function __construct(TagsRepository $tagsRepository)
    {
        $this->tagsRepository = $tagsRepository;
    }
    /**
     * For the total A column in either the fixed or flex budget table.
     * @param $tags
     * @param $type
     * @return int
     */
    public function getBudget($tags, $type)
    {
        $total = 0;
        $string = $type . '_budget';

        foreach ($tags as $tag) {
            $total += $tag->$string;
        }

        return $total;
    }

    /**
     * Get total expenses, either fixed or flex, after starting date
     * For the total '-' column in either the fixed or flex budget table.
     * @return int
     */
    public function getSpentAfterSD($tags)
    {
        $total = 0;

        foreach ($tags as $tag) {
            $total += $tag->getSpentAfterSD();
        }

        return $total;
    }

    /**
     * Get total expenses, either fixed or flex, before starting date.
     * For the total '-' column in either the fixed or flex budget table.
     * @return int
     */
    public function getSpentBeforeSD($tags)
    {
        $total = 0;

        foreach ($tags as $tag) {
            $total += $tag->getSpentBeforeSD();
        }

        return $total;
    }

    /**
     * Get total received on after CSD, either fixed or flex.
     * For the total '+' column in either the fixed or flex budget table.
     * @return int
     */
    public function getReceivedAfterSD($tags)
    {
        $total = 0;

        foreach ($tags as $tag) {
            $total += $tag->getReceivedAfterSD();
        }

        return $total;
    }

    /**
     * For the total C column in the fixed budget table
     * @return int
     */
    public function getCumulativeBudget()
    {
        $tags = $this->tagsRepository->getTagsWithSpecifiedBudget('fixed');
        $total = 0;

        foreach ($tags as $tag) {
            $total += $tag->getCumulative();
        }

        return $total;
    }

    /**
     * For the total R column in either the fixed or flex budget table
     * @return int
     */
    public function getRemainingBudget($type)
    {
        $tags = $this->tagsRepository->getTagsWithSpecifiedBudget($type);
        $total = 0;

        foreach ($tags as $tag) {
            $total += $tag->remaining;
        }

        return $total;
    }

    /**
     * For the unallocated row in the flex budget table
     * @param $FLB_info
     * @param $RBWEFLB
     * @return array
     */
    public function getUnallocatedFLB($FLB_info, $RBWEFLB)
    {
        $total = $FLB_info['totals']['budget'];
        $budget = 100 - $total;
        return [
            'budget' => $budget,
            'calculated_budget' => $RBWEFLB / 100 * $budget,
            'remaining' => $RBWEFLB / 100 * $budget
        ];
    }
}