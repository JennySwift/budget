<?php

namespace App\Http\Controllers\API;

use App\Events\TransactionWasUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\TransactionTransformer;
use App\Models\Budget;
use App\Models\Savings;
use App\Models\Transaction;
use App\Repositories\Savings\SavingsRepository;
use App\Repositories\Transactions\TransactionsRepository;
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
     * This is for the transaction autocomplete
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $transactions = Transaction::forCurrentUser()
            ->limit(50)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->with('account')
            ->with('budgets');

        if ($request->get('typing') || $request->get('typing') === '') {
            $transactions = $transactions->where($request->get('column'), 'LIKE', '%' . $request->get('typing') . '%')
                ->where('type', '!=', 'transfer');
        }

        $transactions = $transactions->get();

        $transactions = $this->transform($this->createCollection($transactions, new TransactionTransformer))['data'];
        return response($transactions, Response::HTTP_OK);
    }

    /**
     * Get allocation totals
     *
     * @JS:
     * It is good, but not ideal.
     * Returning an AllocationTotal object that you could eventually use
     * with a transformer would be a good idea.
     * But it is fine to keep like this if you want :)

     * @param Transaction $transaction
     * @return Response
     */
    public function show(Transaction $transaction)
    {
        return $transaction->getAllocationTotals();
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
            'date',
            'type',
            'direction',
            'description',
            'merchant',
            'total',
            'reconciled',
            'account_id',
            'budgets',
            'minutes'
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
        if ($request->has('updatingAllocation')) {
            //For one transaction, change the amount that is allocated for one budget
            //Should be PUT api/budgets/{budgets}/transactions/{transactions}

            $type = $request->get('type');
            $value = $request->get('value');
            $budget = Budget::find($request->get('budget_id'));

            if ($type === 'percent') {
                $transaction->updateAllocatedPercent($value, $budget);
            }

            elseif ($type === 'fixed') {
                $transaction->updateAllocatedFixed($value, $budget);
            }

            return [
                "budgets" => $transaction->budgets,
                "totals" => $transaction->getAllocationTotals()
            ];
        }

        else {
            $data = array_filter(array_diff_assoc(
                $request->only([
                    'date',
                    'account_id',
                    'description',
                    'merchant',
                    'total',
                    'type',
                    'reconciled',
                    'allocated',
                    'minutes'
                ]),
                $transaction->toArray()
            ), 'removeFalseKeepZeroAndEmptyStrings');

            //Make the total positive if the type has been changed from expense to income
            if (isset($data['type']) && $transaction->type === 'expense' && $data['type'] === 'income') {
                if (isset($data['total']) && $data['total'] < 0) {
                    //The user has changed the total as well as the type,
                    //but the total is negative and it should be positive
                    $data['total'] = $data['total'] * -1;
                }
                else {
                    //The user has changed the type but not the total
                    $transaction->total = $transaction->total * -1;
                    $transaction->save();
                }
            }

            //Make the total negative if the type has been changed from income to expense
            if (isset($data['type']) && $transaction->type === 'income' && $data['type'] === 'expense') {
                if (isset($data['total']) && $data['total'] > 0) {
                    //The user has changed the total as well as the type,
                    //but the total is positive and it should be negative
                    $data['total'] = $data['total'] * -1;
                }
                else {
                    //The user has changed the type but not the total
                    $transaction->total = $transaction->total * -1;
                    $transaction->save();
                }
            }

//        if(empty($data)) {
//            return $this->responseNotModified();
//        }

            //Fire event
            //Todo: update the savings when event is fired
            event(new TransactionWasUpdated($transaction, $data));

            $transaction->update($data);
            $transaction->save();

            $budgets = $request->get('budgets');

            if (isset($budgets)) {
                $transaction->budgets()->detach();
            }

            if ($budgets) {
                $this->transactionsRepository->attachBudgets($transaction, $budgets);
            }

            $item = $this->createItem(
                $transaction,
                new TransactionTransformer
            );

            return $this->responseWithTransformer($item, Response::HTTP_OK);
        }

    }

    /**
     * Delete a transaction, only if it belongs to the user
     * @param Transaction $transaction
     * @return Response
     * @throws \Exception
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        //Reverse the automatic insertion into savings if it is an income expense
        if ($transaction->type === 'income') {
            $savings = Savings::forCurrentUser()->first();
            $savings->decrease($this->savingsRepository->calculateAmountToSubtract($transaction));
        }

        return $this->responseNoContent();
    }
}
