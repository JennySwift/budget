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
     * @var array
     */
    private $fields = ['date', 'description', 'merchant', 'total', 'type', 'reconciled', 'minutes', 'account_id'];

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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $transactions = Transaction::forCurrentUser()
            ->limit(50)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->with('account')
            ->with('budgets');

        if ($request->exists('filter')) {
            $transactions = $transactions->where($request->get('field'), 'LIKE', '%' . $request->get('filter') . '%')
                ->where('type', '!=', 'transfer');
        }

        $transactions = $transactions->get();

        return $this->respondIndex($transactions, new TransactionTransformer);
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
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $transaction = new Transaction($request->only($this->fields));

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

        $transaction->user()->associate(Auth::user());
        $transaction->account()->associate(Account::findOrFail($request->get('account_id')));
        $transaction->save();

        if ($transaction->type !== 'transfer') {
            $this->budgetTransactionRepository->attachBudgetsWithDefaultAllocation($transaction, $request->get('budget_ids'));
        }

        //Fire event
        event(new TransactionWasCreated($transaction));

        return $this->respondStore($transaction, new TransactionTransformer);
    }

    /**
     *
     * @param Request $request
     * @param Transaction $transaction
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //For adding budgets to many transactions at once
        if ($request->has('addingBudgets')) {
            $transaction = $this->budgetTransactionRepository->addBudgets($request, $transaction);
        }
        else {
            $previousTotal = $transaction->total;
            $data = $this->getData($transaction, $request->only($this->fields));
//            $data = getRequestData($request, $transaction);

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
            else if (isset($data['type']) && $transaction->type === 'income' && $data['type'] === 'expense') {
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
            //Make the total negative if an expense
            else if ($transaction->type === 'expense' && array_key_exists('total', $data) && $data['total'] > 0) {
                $data['total']*=-1;
            }
            //Make the total positive if an income
            else if ($transaction->type === 'income' && array_key_exists('total', $data) && $data['total'] < 0) {
                $data['total']*=-1;
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
            //If the total has changed, update the allocation if the allocation is a percentage of the transaction total
            if ($previousTotal !== $transaction->total) {
                $budgetsToUpdateAllocation = $transaction->budgets()->wherePivot('allocated_percent', '>', 0)->get();
                foreach ($budgetsToUpdateAllocation as $budget) {
                    $calculatedAllocation = $transaction->total / 100 * $budget->pivot->allocated_percent;
                    $transaction->budgets()->updateExistingPivot($budget->id, ['calculated_allocation' => $calculatedAllocation]);
                }
            }
        }

        return $this->respondShow($transaction, new TransactionTransformer);
    }

    /**
     *
     * @param Transaction $transaction
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
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

        return $this->respondDestroy();
    }
}
