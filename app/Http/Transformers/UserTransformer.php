<?php

namespace App\Http\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        $array = [
            'id' => $user->id,
            'name' => $user->name,
        ];

        return $array;
    }

}