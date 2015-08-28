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
        return Tag::forCurrentUser()
                  ->orderBy('name', 'asc')
                  ->get();
    }

}