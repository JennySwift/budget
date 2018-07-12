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
//            'balance' => $account->balance
        ];

        //Including the balance all the time now, because the select box value wasn't working because the account objects didn't have all the same properties
        if (isset($this->params['includeBalance']) && $this->params['includeBalance']) {
            $array['balance'] = $account->balance;
        }

        return $array;
    }

}