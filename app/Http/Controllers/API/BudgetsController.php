<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Budgets\CreateBudgetRequest;
use App\Http\Transformers\BudgetTransformer;
use App\Models\Budget;
use App\Repositories\Budgets\BudgetsRepository;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * @param BudgetsRepository $budgetsRepository
     */
    public function __construct(BudgetsRepository $budgetsRepository)
    {
        $this->budgetsRepository = $budgetsRepository;
    }

    /**
     * This method is only for the test at the moment
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->has('fixed')) {
            $budgets = Budget::forCurrentUser()->whereType('fixed')->get();
        }
        else if ($request->has('flex')) {
//            $budgets = Budget::forCurrentUser()->whereType('flex')->get();
            $remainingBalance = app('remaining-balance')->calculate();
            return $remainingBalance->flexBudgetTotals->budgets['data'];
        }
        else {
            $budgets = Budget::forCurrentUser()->get();
        }

        $budgets = $this->transform($this->createCollection($budgets, new BudgetTransformer))['data'];
        return response($budgets, Response::HTTP_OK);
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

        $item = $this->createItem(
            $budget,
            new BudgetTransformer
        );

        return $this->responseWithTransformer($item, Response::HTTP_CREATED);
    }

    /**
     * GET api/budgets{budgets}
     */
    public function show(Budget $budget)
    {
        return $this->responseOk($budget);
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
        $data = array_filter(array_diff_assoc($request->only(['name', 'type', 'amount', 'starting_date']), $budget->toArray()));

        if(empty($data)) {
            return response($this->transform($this->createItem($budget, new BudgetTransformer)), Response::HTTP_NOT_MODIFIED);
        }

        $budget->update($data);

        //Put the calculated amount attribute on the budget
        $remainingBalance = app('remaining-balance')->calculate();
        $budget->getCalculatedAmount($remainingBalance);

        return $this->responseOkWithTransformer($budget, new BudgetTransformer);
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
