<?php

namespace App\Http\Controllers\API;

use App\Events\TransactionWasCreated;
use App\Events\TransactionWasUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\TransactionTransformer;
use App\Models\Budget;
use App\Models\Savings;
use App\Models\Transaction;
use App\Repositories\Savings\SavingsRepository;
use App\Repositories\Transactions\TransactionsRepository;
use Auth;
use DB;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class TransactionsController
 * @package App\Http\Controllers
 */
class TransactionsController extends Controller
{
    /**
     * @var TransactionsRepository
     */
    protected $transactionsRepository;

    /**
     * @var SavingsRepository
     */
    private $savingsRepository;

    /**
     * @param TransactionsRepository $transactionsRepository
     */
    public function __construct(TransactionsRepository $transactionsRepository, SavingsRepository $savingsRepository)
    {
        $this->transactionsRepository = $transactionsRepository;
        $this->savingsRepository = $savingsRepository;
    }

    /**
     * Delete a transaction, only if it belongs to the user
     * @param Request $request
     * @return Response
     */
    public function destroy($transaction)
    {
        $transaction->delete();

        //Reverse the automatic insertion into savings if it is an income expense
        if ($transaction->type === 'income') {
            $savings = Savings::forCurrentUser()->first();
            $savings->decrease($this->savingsRepository->calculateAmountToSubtract($transaction));
        }

        return $this->responseNoContent();
    }

    public function show($transaction)
    {
        return $this->responseOk($transaction);
//        return $this->responseWithTransformer($item, Response::HTTP_OK);
    }

    /**
     * Todo: Should be POST /api/accounts/{accounts}/transaction
     * Todo: Do validations
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'date', 'type', 'direction', 'description', 'merchant', 'total', 'reconciled', 'account_id', 'budgets'
        ]);

        $transaction = $this->transactionsRepository->create($data);

        $item = $this->createItem(
            $transaction,
            new TransactionTransformer
        );

        return $this->responseWithTransformer($item, Response::HTTP_CREATED);
    }

    /**
     * Update the transaction
     * PUT api/transactions/{transactions}
     * @param Request $request
     * @param Transaction $transaction
     * @return Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $data = array_filter(array_diff_assoc(
            $request->only([
                'date', 'account_id', 'description', 'merchant', 'total', 'reconciled', 'allocated'
            ]),
            $transaction->toArray()
        ), 'removeFalseKeepZero');

//        if(empty($data)) {
//            return $this->responseNotModified();
//        }

        //Fire event
        //Todo: update the savings when event is fired
        event(new TransactionWasUpdated($transaction, $data));

        $transaction->update($data);
        $transaction->save();

        if ($request->get('budgets')) {
            $transaction->budgets()->detach();
            $this->transactionsRepository->attachBudgets($transaction, $request->get('budgets'));
        }

        $item = $this->createItem(
            $transaction,
            new TransactionTransformer
        );

        return $this->responseWithTransformer($item, Response::HTTP_CREATED);
    }

    /**
     * For one transaction, change the amount that is allocated for one tag
     * POST api/updateAllocation
     *
     * One route to update allocation for transactions linked to multiple budgets
     * PUT api/budgets/{budgets}/transactions/{transactions} => ['type' => 'percent', 'amount' => 75]
     *
     * @param Request $request
     * @return array
     */
    public function updateAllocation(Request $request)
    {
        $type = $request->get('type');
        $value = $request->get('value');
        $transaction = Transaction::find($request->get('transaction_id'));
        $budget = Budget::find($request->get('budget_id'));

        if ($type === 'percent') {
            $transaction->updateAllocatedPercent($value, $budget);
        }
        elseif ($type === 'fixed') {
            $transaction->updateAllocatedFixed($value, $budget);
        }

        return [
//            "allocation_info" => $tag->getAllocationInfo($transaction, $tag),
            "budgets" => $transaction->budgets,
            "totals" => $transaction->getAllocationTotals()
        ];
    }

    /**
     * Get allocation totals
     * POST /select/allocationTotals
     * @param Request $request
     * @return array
     */
    public function getAllocationTotals(Request $request)
    {
        $transaction = Transaction::find($request->get('transaction_id'));

        // It is good, but not ideal. Returning an AllocationTotal object that you could eventually use
        // with a transformer would be a good idea. But it is fine to keep like this if you want :)
        return $transaction->getAllocationTotals();
    }
}
