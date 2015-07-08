<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Budget;
use App\Models\Tag;
use App\Models\Transaction;
use App\Repositories\Transactions\TransactionsRepository;
use Auth;
use Carbon\Carbon;
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
     * For the new transaction allocation popup.
     * Probably selecting things here that I don't actually need
     * for just the popup.
     * @param $transaction_id
     * @return mixed
     */
    public function getTransaction($transaction_id)
    {
        $transaction = Transaction::where('transactions.id', $transaction_id)
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->select('allocated', 'transactions.id', 'date', 'type', 'transactions.account_id AS account_id',
                'accounts.name AS account_name', 'merchant', 'description', 'reconciled', 'total', 'date')
            ->first();

        $date = $transaction->date;
        $transaction->user_date = Transaction::convertDate($date, 'user');

        return $transaction;
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
            Transaction::insertTransaction($new_transaction, $type);
        } else {
            //It's a transfer, so insert two transactions, the from and the to
            Transaction::insertTransaction($new_transaction, "from");
            Transaction::insertTransaction($new_transaction, "to");
        }

        //Check if the transaction that was just entered has multiple budgets.
        //Note for transfers this won't do both of them.
        $last_transaction_id = Transaction::getLastTransactionId();
        $transaction = $this->getTransaction($last_transaction_id);
        $multiple_budgets = Transaction::hasMultipleBudgets($last_transaction_id);

        $array = array(
            "transaction" => $transaction,
            "multiple_budgets" => $multiple_budgets
        );

        return $array;
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
        } elseif ($type === 'fixed') {
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
        Transaction::where('id', $request->get('transaction_id'))
            ->update([
                'allocated' => $request->get('status')
            ]);
    }

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

        Transaction::insertTags($transaction_id, $transaction['tags'], $total);
    }

    /**
     *
     * @param Request $request
     */
    public function updateReconciliation(Request $request, TransactionsRepository $transactionsRepository)
    {
        $reconciled = $transactionsRepository->convertFromBoolean($request->get('reconciled'));
        Transaction::where('id', $request->get('id'))
            ->update([
                'reconciled' => $reconciled
            ]);
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
}
