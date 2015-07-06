<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Budget;
use Auth;
use DB;
use Illuminate\Http\Request;

class BudgetsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        return view('budgets');
    }

    public function updateBudget(Request $request)
    {
        //this either adds or deletes a budget, both using an update query.
        $tag_id = $request->get('tag_id');
        $budget = $request->get('budget');
        $column = $request->get('column');

        Budget::updateBudget($tag_id, $budget, $column);
    }

    public function updateAllocation(Request $request)
    {
        $type = $request->get('type');
        $value = $request->get('value');
        $transaction_id = $request->get('transaction_id');
        $tag_id = $request->get('tag_id');

        if ($type === 'percent') {
            Budget::updateAllocatedPercent($value, $transaction_id, $tag_id);
        } elseif ($type === 'fixed') {
            Budget::updateAllocatedFixed($value, $transaction_id, $tag_id);
        }

        //get the updated tag info after the update
        $allocation_info = Budget::getAllocationInfo($transaction_id, $tag_id);
        $allocation_totals = Budget::getAllocationTotals($transaction_id);

        $array = array(
            "allocation_info" => $allocation_info,
            "allocation_totals" => $allocation_totals
        );

        return $array;
    }

    public function updateAllocationStatus(Request $request)
    {
        $transaction_id = $request->get('transaction_id');
        $status = $request->get('status');

        Budget::updateAllocationStatus($transaction_id, $status);
    }

    public function updateStartingDate()
    {

    }

    public function updateCSD(Request $request)
    {
        $tag_id = $request->get('tag_id');
        $CSD = $request->get('CSD');

        DB::table('tags')->where('id', $tag_id)->update(['starting_date' => $CSD]);
    }

    /**
     * delete
     */

}
