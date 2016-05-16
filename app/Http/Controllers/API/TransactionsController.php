<?php

namespace App\Http\Controllers\API;

use App\Events\TransactionWasCreated;
use App\Events\TransactionWasUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\TransactionTransformer;
use App\Models\Account;
use App\Models\Budget;
use Auth;
use App\Models\Savings;
use App\Models\Transaction;
use App\Repositories\Savings\SavingsRepository;
use App\Repositories\Transactions\TransactionsRepository;
use App\Repositories\Transactions\TransactionsUpdateRepository;
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
     * @var TransactionsUpdateRepository
     */
    private $transactionsUpdateRepository;

    /**
     * @param TransactionsRepository $transactionsRepository
     * @param SavingsRepository $savingsRepository
     * @param TransactionsUpdateRepository $transactionsUpdateRepository
     */
    public function __construct(TransactionsRepository $transactionsRepository, SavingsRepository $savingsRepository, TransactionsUpdateRepository $transactionsUpdateRepository)
    {
        $this->transactionsRepository = $transactionsRepository;
        $this->savingsRepository = $savingsRepository;
        $this->transactionsUpdateRepository = $transactionsUpdateRepository;
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
            $transaction->total*= -1;
        }
        else if ($transaction->type === 'income' && $transaction->total < 0) {
            $transaction->total*= -1;
        }
        else if ($transaction->type === 'transfer' && $request->get('direction') === Transaction::DIRECTION_FROM) {
            $transaction->total*= -1;
        }

        $transaction->account()->associate(Account::find($request->get('account_id')));
        $transaction->user()->associate(Auth::user());
        $transaction->save();

        if ($transaction->type !== 'transfer') {
            //Budgets should be sent in the form of [1,2,3,4]?
            $this->transactionsRepository->attachBudgetsWithDefaultAllocation($transaction, $request->get('budgets'));
        }

        //Fire event
        event(new TransactionWasCreated($transaction));

        $transaction = $this->transform($this->createItem($transaction, new TransactionTransformer))['data'];

        return response($transaction, Response::HTTP_CREATED);
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
        //For adding budgets to many transactions at once
        if ($request->has('addingBudgets')) {
            $transaction = $this->transactionsUpdateRepository->addBudgets($request, $transaction);
        }

        else {
            $transaction = $this->transactionsUpdateRepository->updateTransaction($request, $transaction);
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
