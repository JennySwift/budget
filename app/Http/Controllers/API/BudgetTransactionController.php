<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Transformers\TransactionTransformer;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Http\Request;

class BudgetTransactionController extends Controller
{
    /**
     * PUT /api/budgets/{budgets}/transactions/{transactions}
     * For one transaction, change the amount that is allocated for one budget
     * @param Request $request
     * @param Budget $budget
     * @param Transaction $transaction
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, Budget $budget, Transaction $transaction)
    {
        $type = $request->get('type');
        $value = $request->get('value');

        if ($type === 'percent') {
            $transaction->updateAllocatedPercent($value, $budget);
        } elseif ($type === 'fixed') {
            $transaction->updateAllocatedFixed($value, $budget);
        }

        return $this->respondUpdate($transaction, new TransactionTransformer);
    }
}
