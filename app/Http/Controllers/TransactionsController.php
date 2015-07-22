<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Budget;
use App\Models\Savings;
use App\Models\Tag;
use App\Models\Transaction;
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
     * @var TotalsService
     */
    protected $totalsService;

    /**
     * @param TransactionsRepository $transactionsRepository
     * @param TotalsService $totalsService
     */
    public function __construct(TransactionsRepository $transactionsRepository, TotalsService $totalsService)
    {
        $this->transactionsRepository = $transactionsRepository;
        $this->totalsService = $totalsService;
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
    public function filterTransactions(Request $request, TransactionsRepository $transactionsRepository)
    {
        return $transactionsRepository->filterTransactions($request->get('filter'));
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
     *
     * @param Request $request
     */
    public function deleteTransaction(Request $request)
    {
        $transaction = Transaction::find($request->get('transaction')['id']);
        $transaction->delete();

        //Reverse the automatic insertion into savings if it is an income expense
        if ($transaction->type === 'income') {
            Savings::calculateAmountToSubtract($transaction);
        }

        return [
            'totals' => $this->totalsService->getBasicAndBudgetTotals($this->budgetService),
            'filter_results' => $this->transactionsRepository->filterTransactions($request->get('filter'))
        ];
    }

    /**
     * Insert tags into transaction
     * @param $transaction
     * @param $tags
     * @param $transaction_total
     */
    public function insertTags($transaction, $tags)
    {
        foreach ($tags as $tag) {
            $tag_id = $tag['id'];

            if (isset($tag['allocated_fixed'])) {
                $tag_allocated_fixed = $tag['allocated_fixed'];

                $transaction->tags()->attach($tag_id, [
                    'allocated_fixed' => $tag_allocated_fixed,
                    'calculated_allocation' => $tag_allocated_fixed
                ]);

            }
            elseif (isset($tag['allocated_percent'])) {
                $tag_allocated_percent = $tag['allocated_percent'];

                $transaction->tags()->attach($tag_id, [
                    'allocated_percent' => $tag_allocated_percent,
                    'calculated_allocation' => $transaction->total / 100 * $tag_allocated_percent,
                ]);

            }
            else {
                $transaction->tags()->attach($tag_id, [
                    'calculated_allocation' => $transaction->total,
                ]);
            }
        }
    }

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
     * @param Request $request
     * @return array
     */
    public function insertTransaction(Request $request, TransactionsRepository $transactionsRepository)
    {
        $new_transaction = $request->get('new_transaction');
        $type = $new_transaction['type'];

        //Insert income or expense transaction
        if ($type !== "transfer") {
            $this->reallyInsertTransaction($new_transaction, $type);
        }

        //It's a transfer, so insert two transactions, the from and the to
        else {
            $this->reallyInsertTransaction($new_transaction, "from");
            $this->reallyInsertTransaction($new_transaction, "to");
        }

        //Find the last transaction that was entered
        $transaction = Transaction::with('tags')->find(Transaction::getLastTransactionId());

        // Put an amount into savings if it is an income expense
        if ($transaction->type === 'income') {
            Savings::add($transaction);
        }

        // Todo: Check both transactions for multiple budgets, not just the last one?

        return [
            "transaction" => $transaction,
            "multiple_budgets" => Transaction::hasMultipleBudgets($transaction->id),
            'totals' => $this->totalsService->getBasicAndBudgetTotals($this->budgetService),
            'filter_results' => $transactionsRepository->filterTransactions($request->get('filter'))
        ];
    }

    /**
     *
     * @param $new_transaction
     * @param $transaction_type
     */
    public function reallyInsertTransaction($new_transaction, $transaction_type)
    {
        $transaction = new Transaction([
            'date' => $new_transaction['date']['sql'],
            'description' => $new_transaction['description'],
            'type' => $new_transaction['type'],
            'reconciled' => Transaction::convertFromBoolean($new_transaction['reconciled']),
        ]);

        if ($transaction_type === "from") {
            $transaction->account_id = $new_transaction['from_account'];
            $transaction->total = $new_transaction['negative_total'];
        }
        elseif ($transaction_type === "to") {
            $transaction->account_id = $new_transaction['to_account'];
            $transaction->total = $new_transaction['total'];
        }
        elseif ($transaction_type === 'income' || $transaction_type === 'expense') {
            $transaction->account_id = $new_transaction['account'];
            $transaction->merchant = $new_transaction['merchant'];
            $transaction->total = $new_transaction['total'];
        }

        $transaction->user()->associate(Auth::user());
        $transaction->save();

        //inserting tags
        $this->insertTags(
            $transaction,
            $new_transaction['tags']
        );

        return $transaction;
    }

    //Todo: Combine the update methods below into one method
    // Create an array with the new fields merged
//     $data = array_compare($exercise->toArray(), $request->get('exercise'));

    // Update the model with this array
//     $exercise->update($data);

    /**
     * Update the transaction
     * @param Request $request
     */
    public function update(Request $request)
    {
        $js_transaction = $request->get('transaction');
        $transaction = Transaction::find($js_transaction['id']);
        $previous_total = $transaction->total;
        $new_total = $js_transaction['total'];

        // If it is an income transaction, and if the total has decreased,
        // remove a percentage from savings
        if ($transaction->type === 'income' && $new_total < $previous_total) {
            Savings::calculateAfterDecrease($previous_total, $new_total);
        }

        // If it is an income transaction, and if the total has increased,
        // add a percentage to savings
        if ($transaction->type === 'income' && $new_total > $previous_total) {
            Savings::calculateAfterIncrease($previous_total, $new_total);
        }

        $transaction->update([
            'account_id' => $js_transaction['account']['id'],
            'type' => $js_transaction['type'],
            'date' => $js_transaction['date']['sql'],
            'merchant' => $js_transaction['merchant'],
            'total' => $new_total,
            'description' => $js_transaction['description'],
            'reconciled' => Transaction::convertFromBoolean($js_transaction['reconciled'])
        ]);

        //delete all previous tags for the transaction and then add the current ones
        Transaction::deleteAllTagsForTransaction($transaction);

        $transaction->save();

        $this->insertTags($transaction, $js_transaction['tags']);

        return [
            'totals' => $this->totalsService->getBasicAndBudgetTotals($this->budgetService),
            'filter_results' => $this->transactionsRepository->filterTransactions($request->get('filter'))
        ];
    }

    /**
     *
     * @param Request $request
     */
    public function updateAllocationStatus(Request $request)
    {
        $transaction = Transaction::find($request->get('transaction_id'));
        $transaction->allocated = $request->get('status');
        $transaction->save();
    }

    /**
     *
     * @param Request $request
     */
    public function updateReconciliation(Request $request, TransactionsRepository $transactionsRepository)
    {
        $transaction = Transaction::find($request->get('id'));
        $transaction->reconciled = $transactionsRepository->convertFromBoolean($request->get('reconciled'));
        $transaction->save();

        return [
            'totals' => $this->totalsService->getBasicAndBudgetTotals($this->budgetService),
            'filter_results' => $this->transactionsRepository->filterTransactions($request->get('filter'))
        ];
    }

    /**
     * For one transaction, change the amount that is allocated for one tag
     * @param Request $request
     * @return array
     */
    public function updateAllocation(Request $request)
    {
        $type = $request->get('type');
        $value = $request->get('value');
        $transaction = Transaction::find($request->get('transaction_id'));
        $tag_id = $request->get('tag_id');

        if ($type === 'percent') {
            Transaction::updateAllocatedPercent($value, $transaction, $tag_id);
        }
        elseif ($type === 'fixed') {
            Transaction::updateAllocatedFixed($value, $transaction, $tag_id);
        }

        //get the updated tag info after the update
        $allocation_info = Tag::getAllocationInfo($transaction->id, $tag_id);
        $allocation_totals = Transaction::getAllocationTotals($transaction->id);

        $array = array(
            "allocation_info" => $allocation_info,
            "allocation_totals" => $allocation_totals
        );

        return $array;
    }
}
