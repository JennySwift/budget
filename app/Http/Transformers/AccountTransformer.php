<?php

namespace App\Http\Transformers;

use App\Models\Account;
use League\Fractal\TransformerAbstract;

/**
 * Class AccountTransformer
 */
class AccountTransformer extends TransformerAbstract
{
    private $params;

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     * @param Account $account
     * @return array
     */
    public function transform(Account $account)
    {
        $array = [
            'id' => $account->id,
            'name' => $account->name,
        ];

        if (isset($this->params['includeBalance']) && $this->params['includeBalance']) {
            $array['balance'] = $account->balance;
        }

        return $array;
    }

}