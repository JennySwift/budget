<?php

namespace App\Http\Controllers\API;

use App\Events\TransactionWasCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\TransactionTransformer;
use App\Models\Budget;
use App\Models\Savings;
use App\Models\Transaction;
use App\Repositories\Savings\SavingsRepository;
use App\Repositories\Filters\FilterRepository;
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
     * @var FilterRepository
     */
    private $filterRepository;
    /**
     * @var SavingsRepository
     */
    private $savingsRepository;

    /**
     * @param TransactionsRepository $transactionsRepository
     */
    public function __construct(TransactionsRepository $transactionsRepository, FilterRepository $filterRepository, SavingsRepository $savingsRepository)
    {
        $this->transactionsRepository = $transactionsRepository;
        $this->filterRepository = $filterRepository;
        $this->savingsRepository = $savingsRepository;
    }

    /**
     * GET api/transactions?limit=40&page=2&account_id=3&type=income
     * POST api/select/filter
     * @param Request $request
     * @param TransactionsRepository $transactionsRepository
     * @return array
     */
    public function filterTransactions(Request $request)
    {
        return $this->filterRepository->filterTransactions($request->get('filter'));
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function autocompleteTransaction(Request $request)
    {
        $typing = '%' . $request->get('typing') . '%';
        $transactions = $this->transactionsRepository->autocompleteTransaction($request->get('column'), $typing);

        return $transactions;
    }

    /**
     *
     */
    public function updateMassDescription()
    {

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

    public function show($id)
    {
        $transaction = Transaction::find($id);

        $item = $this->createItem(
            $transaction,
            new TransactionTransformer
        );

        // Put an amount into savings if it is an income expense
//        if ($transaction->type === 'income') {
//            $savings = Savings::forCurrentUser()->first();
//            $savings->increase($this->savingsRepository->calculateAfterIncomeAdded($transaction));
//        }

        // Todo: Check both transactions for multiple budgets, not just the last one?
        return $this->responseWithTransformer($item, Response::HTTP_OK);
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

        // Put an amount into savings if it is an income expense
//        if ($transaction->type === 'income') {
//            $savings = Savings::forCurrentUser()->first();
//            $savings->increase($this->savingsRepository->calculateAfterIncomeAdded($transaction));
//        }

        return $this->responseWithTransformer($item, Response::HTTP_CREATED);
    }

    //Todo: Combine the update methods below into one method
    // Create an array with the new fields merged
//     $data = array_compare($exercise->toArray(), $request->get('exercise'));

    // Update the model with this array
//     $exercise->update($data);

    /**
     * Update the transaction
     * PUT api/transactions/{transactions}
     * @param Request $request
     */
    public function update(Request $request)
    {
        $js_transaction = $request->get('transaction');
        $transaction = Transaction::find($js_transaction['id']);
        $previous_total = $transaction->total;
        $new_total = $js_transaction['total'];
        $savings = Savings::forCurrentUser()->first();

        // If it is an income transaction, and if the total has decreased,
        // remove a percentage from savings
        if ($transaction->type === 'income' && $new_total < $previous_total) {
            $savings->decrease($this->savingsRepository->calculateAfterDecrease($previous_total, $new_total));
        }

        // If it is an income transaction, and if the total has increased,
        // add a percentage to savings
        if ($transaction->type === 'income' && $new_total > $previous_total) {
            $savings->increase($this->savingsRepository->calculateAfterIncrease($previous_total, $new_total));
        }

        $transaction->update([
            'account_id' => $js_transaction['account']['id'],
            'type' => $js_transaction['type'],
            'date' => $js_transaction['date']['sql'],
            'merchant' => $js_transaction['merchant'],
            'total' => $new_total,
            'description' => $js_transaction['description'],
            'reconciled' => convertFromBoolean($js_transaction['reconciled'])
        ]);

        //delete all previous tags for the transaction and then add the current ones
        Transaction::deleteAllTagsForTransaction($transaction);

        $transaction->save();

        $this->transactionsRepository->attachBudgets($transaction, $js_transaction['budgets']);

        $remainingBalance = app('remaining-balance')->calculate();

        return [
            'filter_results' => $this->filterRepository->filterTransactions($request->get('filter')),

            //totals
            'fixedBudgetTotals' => $remainingBalance->fixedBudgetTotals->toArray(),
            'flexBudgetTotals' => $remainingBalance->flexBudgetTotals->toArray(),
            'basicTotals' => $remainingBalance->basicTotals->toArray(),
            'remainingBalance' => $remainingBalance->amount,
        ];
    }

    /**
     * POST api/updateAllocationStatus
     * @param Request $request
     */
    public function updateAllocationStatus(Request $request)
    {
        $transaction = Transaction::find($request->get('transaction_id'));
        $transaction->allocated = $request->get('status');
        $transaction->save();
    }

    /**
     * POST api/updateReconciliation
     * @param Request $request
     */
    public function updateReconciliation(Request $request)
    {
        $transaction = Transaction::find($request->get('id'));
        $transaction->reconciled = convertFromBoolean($request->get('reconciled'));
        $transaction->save();

        $remainingBalance = app('remaining-balance')->calculate();

        return [
            'filter_results' => $this->filterRepository->filterTransactions($request->get('filter')),

            //totals
            'fixedBudgetTotals' => $remainingBalance->fixedBudgetTotals->toArray(),
            'flexBudgetTotals' => $remainingBalance->flexBudgetTotals->toArray(),
            'basicTotals' => $remainingBalance->basicTotals->toArray(),
            'remainingBalance' => $remainingBalance->amount
        ];
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
