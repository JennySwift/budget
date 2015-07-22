<?php namespace App\Repositories\Tags;

use App\Models\Tag;
use Auth;

class TagsRepository {
    /**
     *
     * @param $user_id
     * @return mixed
     */
    public static function getTagsWithFixedBudget()
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
    public static function getTagsWithFlexBudget()
    {
        $tags = Tag::where('user_id', Auth::user()->id)
            ->whereNotNull('flex_budget')
            ->orderBy('name', 'asc')
            ->get();

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