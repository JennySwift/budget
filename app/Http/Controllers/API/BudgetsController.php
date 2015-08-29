<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Budgets\CreateBudgetRequest;
use App\Models\Budget;
use Auth;
use DB;
use JavaScript;

/**
 * Class BudgetsController
 * @package App\Http\Controllers
 */
class BudgetsController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Create a budget
     * @param CreateBudgetRequest $createBudgetRequest
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBudgetRequest $createBudgetRequest)
    {
        $budget = new Budget($createBudgetRequest->only('type', 'name', 'amount', 'starting_date'));
        $budget->user()->associate(Auth::user());
        $budget->save();

        return $this->responseCreated($budget);
    }

}
