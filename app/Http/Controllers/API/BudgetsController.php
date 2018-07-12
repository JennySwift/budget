<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Budgets\CreateBudgetRequest;
use App\Http\Transformers\BudgetTransformer;
use App\Models\Budget;
use Auth;
use Illuminate\Http\Request;

/**
 * Class BudgetsController
 * @package App\Http\Controllers
 */
class BudgetsController extends Controller
{

    /*
     *
     */
    private $fields = ['type', 'name', 'amount', 'starting_date'];

    /**
     * This method is only for the test at the moment
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        if ($request->has('fixed')) {
            $budgets = Budget::forCurrentUser()
                ->whereType('fixed')
                ->orderBy('name', 'asc')
                ->get();
        } else {
            if ($request->has('unassigned')) {
                $budgets = Budget::forCurrentUser()
                    ->whereType('unassigned')
                    ->orderBy('name', 'asc')
                    ->get();
            } else {
                if ($request->has('flex')) {
//            $budgets = Budget::forCurrentUser()->whereType('flex')->get();
                    $remainingBalance = app('remaining-balance')->calculate();
//            return $remainingBalance->flexBudgetTotals->budgets['data'];
                    $budgets = $remainingBalance->flexBudgetTotals->budgets;
                } else {
                    $budgets = Budget::forCurrentUser()
                        ->orderBy('name', 'asc')
                        ->get();
                }
            }
        }

        return $this->respondIndex($budgets, new BudgetTransformer(['includeExtra' => true]));
    }

    /**
     *
     * @param CreateBudgetRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(CreateBudgetRequest $request)
    {
        $budget = new budget($request->only($this->fields));
        $budget->user()->associate(Auth::user());
        $budget->save();

        if ($budget->type === 'flex') {
            $remainingBalance = app('remaining-balance')->calculate();
            $budget->getCalculatedAmount($remainingBalance);
        }

        return $this->respondStore($budget, new BudgetTransformer(['includeExtra' => true]));
    }

    /**
     *
     * @param Request $request
     * @param Budget $budget
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, Budget $budget)
    {
        return $this->respondShow($budget, new BudgetTransformer);
    }

    /**
     *
     * @param Request $request
     * @param Budget $budget
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, Budget $budget)
    {
        $data = $this->getData($budget, $request->only($this->fields));

        $budget->update($data);

        //Put the calculated amount attribute on the budget
        $remainingBalance = app('remaining-balance')->calculate();

        $budget->getCalculatedAmount($remainingBalance);

        return $this->respondUpdate($budget, new BudgetTransformer(['includeExtra' => true]));
    }

    /**
     *
     * @param Request $request
     * @param Budget $budget
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(Request $request, budget $budget)
    {
        return $this->destroyModel($budget);
    }
}
