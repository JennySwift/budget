<?php namespace App\Repositories\Tags;

use App\Models\Tag;
use Auth;

/**
 * Class TagsRepository
 * @package App\Repositories\Tags
 */
class TagsRepository
{

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

}