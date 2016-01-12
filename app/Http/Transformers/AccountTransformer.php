<?php

namespace App\Http\Transformers;

use App\Models\Account;
use League\Fractal\TransformerAbstract;

/**
 * Class AccountTransformer
 */
class AccountTransformer extends TransformerAbstract
{
    /**
     * @param Account $account
     * @return array
     */
    public function transform(Account $account)
    {
        $array = [
            'id' => $account->id,
            'user_id' => $account->user->id,
            'name' => $account->name,
        ];

        return $array;
    }

}