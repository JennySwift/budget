<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Tag;
use App\Models\Transaction;
use App\Repositories\Transactions\TransactionsRepository;
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

    public function filterTransactions(Request $request, TransactionsRepository $transactionsRepository)
    {
        return $transactionsRepository->filterTransactions($request->get('filter'));
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function autocompleteTransaction(Request $request, TransactionsRepository $transactionsRepository)
    {
        $typing = '%' . $request->get('typing') . '%';
        $transactions = $transactionsRepository->autocompleteTransaction($request->get('column'), $typing);

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
    }

    /**
     *
     * @param Request $request
     */
    public function deleteTransaction(Request $request)
    {
        $transaction = Transaction::find($request->get('transaction_id'));
        $transaction->delete();
    }

    //Todo: Refactor the methods below

    /**
     *
     * @param Request $request
     */
    public function updateTransaction(Request $request)
    {
        $transaction = $request->get('transaction');

        $transaction_id = $transaction['id'];
        $total = $transaction['total'];
        $type = $transaction['type'];

        Transaction::where('id', $transaction_id)
            ->update([
                'account_id' => $transaction['account']['id'],
                'type' => $type,
                'date' => $transaction['date']['sql'],
                'merchant' => $transaction['merchant'],
                'total' => $total,
                'description' => $transaction['description'],
                'reconciled' => Transaction::convertFromBoolean($transaction['reconciled'])
            ]);

        //delete all previous tags for the transaction and then add the current ones
        Transaction::deleteAllTagsForTransaction($transaction_id);

        $this->insertTags($transaction_id, $transaction['tags'], $total);
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
        $transaction_id = $request->get('transaction_id');
        $tag_id = $request->get('tag_id');

        if ($type === 'percent') {
            Transaction::updateAllocatedPercent($value, $transaction_id, $tag_id);
        }
        elseif ($type === 'fixed') {
            Transaction::updateAllocatedFixed($value, $transaction_id, $tag_id);
        }

        //get the updated tag info after the update
        $allocation_info = Tag::getAllocationInfo($transaction_id, $tag_id);
        $allocation_totals = Transaction::getAllocationTotals($transaction_id);

        $array = array(
            "allocation_info" => $allocation_info,
            "allocation_totals" => $allocation_totals
        );

        return $array;
    }

    /**
     *
     * @param Request $request
     * @return array
     */
    public function insertTransaction(Request $request)
    {
        $new_transaction = $request->get('new_transaction');
        $type = $new_transaction['type'];

        if ($type !== "transfer") {
            $this->reallyInsertTransaction($new_transaction, $type);
        }
        else {
            //It's a transfer, so insert two transactions, the from and the to
            $this->reallyInsertTransaction($new_transaction, "from");
            $this->reallyInsertTransaction($new_transaction, "to");
        }

        //Check if the transaction that was just entered has multiple budgets.
        //Note for transfers this won't do both of them.
        $last_transaction_id = Transaction::getLastTransactionId();
        $transaction = Transaction::find($last_transaction_id);
        $multiple_budgets = Transaction::hasMultipleBudgets($last_transaction_id);

        $array = array(
            "transaction" => $transaction,
            "multiple_budgets" => $multiple_budgets
        );

        return $array;
    }

    /**
     *
     * @param $new_transaction
     * @param $transaction_type
     */
    public function reallyInsertTransaction($new_transaction, $transaction_type)
    {
        $user_id = Auth::user()->id;
        $date = $new_transaction['date']['sql'];
        $description = $new_transaction['description'];
        $type = $new_transaction['type'];
        $reconciled = $new_transaction['reconciled'];
        $reconciled = Transaction::convertFromBoolean($reconciled);
        $tags = $new_transaction['tags'];

        if ($transaction_type === "from") {
            $from_account = $new_transaction['from_account'];
            $total = $new_transaction['negative_total'];

            Transaction::insert([
                'account_id' => $from_account,
                'date' => $date,
                'total' => $total,
                'description' => $description,
                'type' => $type,
                'reconciled' => $reconciled,
                'user_id' => Auth::user()->id
            ]);
        }
        elseif ($transaction_type === "to") {
            $to_account = $new_transaction['to_account'];
            $total = $new_transaction['total'];

            Transaction::insert([
                'account_id' => $to_account,
                'date' => $date,
                'total' => $total,
                'description' => $description,
                'type' => $type,
                'reconciled' => $reconciled,
                'user_id' => Auth::user()->id
            ]);
        }
        elseif ($transaction_type === 'income' || $transaction_type === 'expense') {
            $account = $new_transaction['account'];
            $merchant = $new_transaction['merchant'];
            $total = $new_transaction['total'];

            Transaction::insert([
                'account_id' => $account,
                'date' => $date,
                'merchant' => $merchant,
                'total' => $total,
                'description' => $description,
                'type' => $type,
                'reconciled' => $reconciled,
                'user_id' => Auth::user()->id
            ]);
        }

        //inserting tags
        $last_transaction_id = Transaction::getLastTransactionId();
        $this->insertTags($last_transaction_id, $tags, $total);
    }

    /**
     * Insert tags into transaction
     * @param $transaction_id
     * @param $tags
     * @param $transaction_total
     */
    public function insertTags($transaction_id, $tags, $transaction_total)
    {
        $transaction = Transaction::find($transaction_id);

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
                    'calculated_allocation' => $transaction_total / 100 * $tag_allocated_percent,
                ]);

            }
            else {
                $transaction->tags()->attach($tag_id, [
                    'calculated_allocation' => $transaction_total,
                ]);
            }
        }
    }
}
