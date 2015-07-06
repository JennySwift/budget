<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Budget;
use Auth;
use DB;
use Illuminate\Http\Request;

/**
 * Class BudgetsController
 * @package App\Http\Controllers
 */
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
}
