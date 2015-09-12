<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Budgets\CreateBudgetRequest;
use App\Models\Budget;
use App\Repositories\Budgets\BudgetsRepository;
use App\Repositories\Tags\TagsRepository;
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
     * @var budgetsRepository
     */
    protected $budgetsRepository;

    /**
     * Create a new controller instance.
     */
    public function __construct(BudgetsRepository $budgetsRepository)
    {
        $this->middleware('auth');
        $this->budgetsRepository = $budgetsRepository;
    }

    /**
     * Create a budget
     * POST api/budgets
     * @param CreateBudgetRequest $createBudgetRequest
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBudgetRequest $createBudgetRequest)
    {
        $budget = new Budget($createBudgetRequest->only('type', 'name', 'amount', 'starting_date'));
        $budget->user()->associate(Auth::user());
        $budget->save();

        if ($budget->type === 'flex') {
            $remainingBalance = app('remaining-balance')->calculate();
            $budget->getCalculatedAmount($remainingBalance);
        }

        return $this->responseCreated($budget);
    }

    /**
     * Update the starting date for a budget
     * PUT api/budgets/{budgets}
     * @TODO Needs refactoring!!!!
     * @param Request $request
     * @param budget $budget
     * @return array
     */
    public function update(Request $request, Budget $budget)
    {
        $data = array_compare($budget->toArray(), $request->get('budget'));
        $budget->update($data);


//        $budget->name = $request->get('name');
//        $budget->type = $request->get('type');
//        $budget->amount = $request->get('amount');

//        $budget->save();

        $remainingBalance = app('remaining-balance')->calculate();

        return [
            'budget' => $budget,

            //totals
            'fixedBudgetTotals' => $remainingBalance->fixedBudgetTotals->toArray(),
            'flexBudgetTotals' => $remainingBalance->flexBudgetTotals->toArray(),
            'basicTotals' => $remainingBalance->basicTotals->toArray(),
            'remainingBalance' => $remainingBalance->amount,
        ];
    }

    /**
     * Delete a budget
     * DELETE api/budgets/{budgets}
     * @param Budget $budget
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(Budget $budget)
    {
        $budget->delete();

        return response([], 204);
    }
}
