<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Budget;
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
        $tag_id = $request->get('tag_id');
        $sql = "SELECT COUNT(*) FROM transactions_tags WHERE tag_id = $tag_id";
        $count = DB::table('transactions_tags')->where('tag_id', $tag_id)->count();

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
    public function autocompleteTransaction(Request $request)
    {
        $typing = $request->get('typing');
        $typing = '%' . $typing . '%';
        $column = $request->get('column');
        $transactions = Transaction::autocompleteTransaction($column, $typing);

        return $transactions;
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
        $transaction = Transaction::getTransaction($last_transaction_id);
        $multiple_budgets = Budget::hasMultipleBudgets($last_transaction_id);

        $array = array(
            "transaction" => $transaction,
            "multiple_budgets" => $multiple_budgets
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
        $transaction_id = $request->get('transaction_id');
        $status = $request->get('status');

        Transaction::where('id', $transaction_id)
            ->update([
                'allocated' => $status
            ]);
    }

    /**
     *
     * @param Request $request
     */
    public function updateTransaction(Request $request)
    {
        $transaction = $request->get('transaction');
        Transaction::updateTransaction($transaction);
    }

    /**
     *
     * @param Request $request
     */
    public function updateReconciliation(Request $request, TransactionsRepository $transactionsRepository)
    {
        $id = $request->get('id');
        $reconciled = $request->get('reconciled');
        $reconciled = $transactionsRepository->convertFromBoolean($reconciled);
        DB::table('transactions')->where('id', $id)->update(['reconciled' => $reconciled]);
    }

    /**
     *
     * @param Request $request
     */
    public function deleteTransaction(Request $request)
    {
        $transaction_id = $request->get('transaction_id');
        DB::table('transactions')->where('id', $transaction_id)->delete();
    }
}
