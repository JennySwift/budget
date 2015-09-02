<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Budget;
use App\Models\Savings;
use App\Models\Tag;
use App\Models\Totals\BasicTotal;
use App\Models\Totals\FixedBudgetTotal;
use App\Models\Totals\FlexBudgetTotal;
use App\Models\Totals\RemainingBalance;
use App\Models\Transaction;
use App\Repositories\Savings\SavingsRepository;
use App\Repositories\Transactions\FilterRepository;
use App\Repositories\Transactions\TransactionsRepository;
use App\Services\TotalsService;
use Auth;
use DB;
use Debugbar;
use Illuminate\Http\Request;

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
     * @param TotalsService $totalsService
     */
    public function __construct(TransactionsRepository $transactionsRepository, TotalsService $totalsService, FilterRepository $filterRepository, SavingsRepository $savingsRepository)
    {
        $this->transactionsRepository = $transactionsRepository;
        $this->totalsService = $totalsService;
        $this->filterRepository = $filterRepository;
        $this->savingsRepository = $savingsRepository;
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function countTransactionsWithTag(Request $request)
    {
        $count = DB::table('transactions_tags')
            ->where('tag_id', $request->get('tag_id'))
            ->count();

        return $count;
    }

    /**
     *
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
     * @VP:
     * I tried using the destroy method instead of this (see method below),
     * but I need to send the filter in the request (for the response), not just the id.
     * ??
     * @param Request $request
     */
    public function deleteTransaction(Request $request)
    {
        $transaction = Transaction::find($request->get('transaction')['id']);
        $transaction->delete();

        //Reverse the automatic insertion into savings if it is an income expense
        if ($transaction->type === 'income') {
            $savings = Savings::forCurrentUser()->first();
            $savings->decrease($this->savingsRepository->calculateAmountToSubtract($transaction));
        }

        return [
            'totals' => $this->totalsService->getBasicAndBudgetTotals(),
            'filter_results' => $this->filterRepository->filterTransactions($request->get('filter'))
        ];
    }

    /**
     * Delete a transaction, only if it belongs to the user
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
//    public function destroy($id)
//    {
//        $transaction = Transaction::forCurrentUser()->findOrFail($id);
//        $transaction->delete();
//
//        //Reverse the automatic insertion into savings if it is an income expense
//        if ($transaction->type === 'income') {
//            $this->savingsRepository->calculateAmountToSubtract($transaction);
//        }
//
//        //Todo: return response code
//        return [
//            'totals' => $this->totalsService->getBasicAndBudgetTotals(),
//            'filter_results' => $this->filterRepository->filterTransactions($request->get('filter'))
//        ];
//    }

    /**
     *
     * For Postman:
     *
     * {"new_transaction": {
     *
     * "total": -5,
     * "type": "expense",
     * "description": "",
     * "merchant": "",
     * "date": {
     * "entered": "today",
     * "sql": "2015-07-08"
     * },
     * "reconciled": false,
     * "multiple_budgets": false,
     * "reconciled": false,
     * "multiple_budgets": false,
     * "account": 1,
     * "from_account": 1,
     * "to_account": 1,
     * "tags": [
     * {
     * "id": 5,
     * "created_at": "2015-07-08 06:37:07",
     * "updated_at": "2015-07-08 06:37:07",
     * "name": "books",
     * "fixed_budget": "10.00",
     * "flex_budget": null,
     * "starting_date": "2015-01-01",
     * "budget_id": 1,
     * "user_id": 1
     * }
     * ]
     *
     * }
     * }
     *
     * POST api/transactions/{transactions}
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $new_transaction = $request->get('new_transaction');
        $type = $new_transaction['type'];

        //Insert income or expense transaction
        if ($type !== "transfer") {
            $this->transactionsRepository->insertTransaction($new_transaction, $type);
        } //It's a transfer, so insert two transactions, the from and the to
        else {
            $this->transactionsRepository->insertTransaction($new_transaction, "from");
            $this->transactionsRepository->insertTransaction($new_transaction, "to");
        }

        //Find the last transaction that was entered
        $transaction = Transaction::with('budgets')->find(Transaction::getLastTransactionId());

        // Put an amount into savings if it is an income expense
        if ($transaction->type === 'income') {
            $savings = Savings::forCurrentUser()->first();
            $savings->increase($this->savingsRepository->calculateAfterIncomeAdded($transaction));
        }

        // Todo: Check both transactions for multiple budgets, not just the last one?

        $remainingBalance = app('remaining-balance')->calculate();

        return [
            "transaction" => $transaction,
            "multiple_budgets" => $transaction->hasMultipleBudgets(),
            'filter_results' => $this->filterRepository->filterTransactions($request->get('filter')),

            //totals
            'fixedBudgetTotals' => $remainingBalance->fixedBudgetTotals->toArray(),
            'flexBudgetTotals' => $remainingBalance->flexBudgetTotals->toArray(),
            'basicTotals' => $remainingBalance->basicTotals->toArray(),
            'remainingBalance' => $remainingBalance->amount,
        ];
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

        $this->transactionsRepository->insertTags($transaction, $js_transaction['tags']);

        return [
            'totals' => $this->totalsService->getBasicAndBudgetTotals(),
            'filter_results' => $this->filterRepository->filterTransactions($request->get('filter'))
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

        return [
            'totals' => $this->totalsService->getBasicAndBudgetTotals(),
            'filter_results' => $this->filterRepository->filterTransactions($request->get('filter'))
        ];
    }

    /**
     * For one transaction, change the amount that is allocated for one tag
     * POST api/updateAllocation
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
