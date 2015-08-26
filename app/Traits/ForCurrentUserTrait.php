<?php

namespace App\Traits;

use Auth;

/**
 * Trait ForCurrentUserTrait
 * @package App\Traits
 */
trait ForCurrentUserTrait
{
    /**
     *
     * @param $query
     * @return mixed
     */
    public function scopeForCurrentUser($query)
    {
        return $query->whereUserId(Auth::id());
    }

}