<?php

namespace App\Http\Controllers\API;

use App\Http\Transformers\TransactionTransformer;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class BudgetTransactionController extends Controller
{

    /**
     * For one transaction, change the amount that is allocated for one budget
     * PUT /api/budgets/{budgets}/transactions/{transactions}
     * @param Request $request
     * @param Budget $budget
     * @param Transaction $transaction
     * @return Response
     */
    public function update(Request $request, Budget $budget, Transaction $transaction)
    {
        $type = $request->get('type');
        $value = $request->get('value');

        if ($type === 'percent') {
            $transaction->updateAllocatedPercent($value, $budget);
        }

        elseif ($type === 'fixed') {
            $transaction->updateAllocatedFixed($value, $budget);
        }

        $transaction = $this->transform($this->createItem($transaction, new TransactionTransformer))['data'];

        return response($transaction, Response::HTTP_OK);
    }
}
