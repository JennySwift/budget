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
     * For the total A column in either the fixed or flex budget table.
     * @param $tags
     * @param $type
     * @return int
     */
    public function getBudget($type)
    {
        $tagsRepository = new TagsRepository();
        $tags = $tagsRepository->getTagsWithSpecifiedBudget($type);
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
    public function getSpentAfterSD($type)
    {
        $tagsRepository = new TagsRepository();
        $tags = $tagsRepository->getTagsWithSpecifiedBudget($type);
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
    public function getSpentBeforeSD($type)
    {
        $tagsRepository = new TagsRepository();
        $tags = $tagsRepository->getTagsWithSpecifiedBudget($type);
        $total = 0;

        foreach ($tags as $tag) {
            $total += $tag->spent_before_SD;
        }

        return $total;
    }

    /**
     * Get total received on after CSD, either fixed or flex.
     * For the total '+' column in either the fixed or flex budget table.
     * @return int
     */
    public function getReceivedAfterSD($type)
    {
        $tagsRepository = new TagsRepository();
        $tags = $tagsRepository->getTagsWithSpecifiedBudget($type);
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
        $tagsRepository = new TagsRepository();
        $tags = $tagsRepository->getTagsWithSpecifiedBudget('fixed');
        $total = 0;

        foreach ($tags as $tag) {
            $total += $tag->cumulative;
        }

        return $total;
    }

    /**
     * For the total R column in either the fixed or flex budget table
     * @return int
     */
    public function getRemainingBudget($type)
    {
        $tagsRepository = new TagsRepository();
        $tags = $tagsRepository->getTagsWithSpecifiedBudget($type);
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