<?php

namespace App\Http\Controllers\API;

use App\Events\TransactionWasCreated;
use App\Events\TransactionWasUpdated;
use App\Http\Controllers\Controller;
use App\Http\Transformers\TransactionTransformer;
use App\Models\Account;
use App\Models\Savings;
use App\Models\Transaction;
use App\Repositories\Savings\SavingsRepository;
use App\Repositories\Transactions\BudgetTransactionRepository;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class TransactionsController
 * @package App\Http\Controllers
 */
class TransactionsController extends Controller
{
    /**
     * @var SavingsRepository
     */
    private $savingsRepository;
    /**
     * @var BudgetTransactionRepository
     */
    private $budgetTransactionRepository;

    /**
     * @param SavingsRepository $savingsRepository
     * @param BudgetTransactionRepository $budgetTransactionRepository
     */
    public function __construct(SavingsRepository $savingsRepository, BudgetTransactionRepository $budgetTransactionRepository) {
        $this->savingsRepository = $savingsRepository;
        $this->budgetTransactionRepository = $budgetTransactionRepository;
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
     * Todo: Do validations
     * POST /api/transactions
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $transaction = new Transaction($request->only([
            'date',
            'description',
            'merchant',
            'total',
            'type',
            'reconciled',
            'minutes'
        ]));

        //Make sure total is negative for expense, negative for transfers from, and positive for income
        if ($transaction->type === 'expense' && $transaction->total > 0) {
            $transaction->total *= -1;
        }
        else {
            if ($transaction->type === 'income' && $transaction->total < 0) {
                $transaction->total *= -1;
            }
            else {
                if ($transaction->type === 'transfer' && $request->get('direction') === Transaction::DIRECTION_FROM) {
                    $transaction->total *= -1;
                }
            }
        }

        $transaction->account()->associate(Account::find($request->get('account_id')));
        $transaction->user()->associate(Auth::user());
        $transaction->save();

        if ($transaction->type !== 'transfer') {
            $this->budgetTransactionRepository->attachBudgetsWithDefaultAllocation($transaction, $request->get('budget_ids'));
        }

        //Fire event
        event(new TransactionWasCreated($transaction));

        $transaction = $this->transform($this->createItem($transaction, new TransactionTransformer))['data'];

        return response($transaction, Response::HTTP_CREATED);
    }

    /**
     * PUT /api/transactions/{transactions}
     * @param Request $request
     * @param Transaction $transaction
     * @return Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //For adding budgets to many transactions at once
        if ($request->has('addingBudgets')) {
            $transaction = $this->budgetTransactionRepository->addBudgets($request, $transaction);
        }
        else {
            $data = array_filter(array_diff_assoc(
                $request->only([
                    'date',
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

            if ($request->has('account_id')) {
                $transaction->account()->associate(Account::findOrFail($request->get('account_id')));
            }

            //Make the total positive if the type has been changed from expense to income
            if (isset($data['type']) && $transaction->type === 'expense' && $data['type'] === 'income') {
                if (isset($data['total']) && $data['total'] < 0) {
                    //The user has changed the total as well as the type,
                    //but the total is negative and it should be positive
                    $data['total'] *= -1;
                }
                else {
                    //The user has changed the type but not the total
                    $transaction->total *= -1;
                    $transaction->save();
                }
            }

            //Make the total negative if the type has been changed from income to expense
            if (isset($data['type']) && $transaction->type === 'income' && $data['type'] === 'expense') {
                if (isset($data['total']) && $data['total'] > 0) {
                    //The user has changed the total as well as the type,
                    //but the total is positive and it should be negative
                    $data['total'] *= -1;
                }
                else {
                    //The user has changed the type but not the total
                    $transaction->total *= -1;
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

            if ($request->has('budget_ids')) {
                $transaction->budgets()->sync($request->get('budget_ids'));

                //Change calculated_allocation from null to 0
                $budgetsAdded = $transaction->budgets()->wherePivot('calculated_allocation', null)->get();
                foreach ($budgetsAdded as $budget) {
                    $transaction->budgets()->updateExistingPivot($budget->id, ['calculated_allocation' => '0']);
                }

            }
        }

        $transaction = $this->transform($this->createItem($transaction, new TransactionTransformer))['data'];

        return response($transaction, Response::HTTP_OK);
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
