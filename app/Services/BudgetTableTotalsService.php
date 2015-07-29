<?php

namespace App\Services;


use App\Models\Total;
use App\Repositories\Tags\TagsRepository;
use App\Repositories\Totals\TotalsRepository;

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

        //I could do this in my tag model
        $string = $type . '_budget';

        //This could be in TagsRepository
        //Foreach is slower on collections
        //Could use map (see codementor example). Foreach efficient if real array.
        foreach ($tags as $tag) {
            $total += $tag->$string;
        }

        return $total;
    }

    /**
     * For the unallocated row in the flex budget table
     * @param $FLB_info
     * @param $RBWEFLB
     * @return array
     */
    public function getUnallocatedFLB()
    {
        $totalsRepository = new TotalsRepository();
        $RBWEFLB = $totalsRepository->getRBWEFLB();
        $unallocated_budget = 100 - $this->getBudget('flex');

        return [
            'budget' => $unallocated_budget,
            'calculated_budget' => $RBWEFLB / 100 * $unallocated_budget,
            'remaining' => $RBWEFLB / 100 * $unallocated_budget
        ];
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
            $total += $tag->spent_after_SD;
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
            $total += $tag->received_after_SD;
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

        if ($type === 'flex') {
            $total+= $this->getUnallocatedFLB()['remaining'];
        }

        return $total;
    }

    /**
     * For the total A column in the flex budget table
     * @return int
     */
    public function getCalculatedBudget()
    {
        $tagsRepository = new TagsRepository();
        $tags = $tagsRepository->getTagsWithSpecifiedBudget('flex');
        $total = 0;

        foreach ($tags as $tag) {
            $total += $tag->calculated_budget;
        }

        $total+= $this->getUnallocatedFLB()['calculated_budget'];

        return $total;
    }
}