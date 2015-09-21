<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Filter;
use Illuminate\Http\Request;
use Debugbar;

/**
 * Class FilterController
 * @package App\Http\Controllers
 */
class FilterController extends Controller
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * Create a new controller instance.
     * @param Filter $filter
     */
    public function __construct(Filter $filter)
    {
        $this->middleware('auth');
        $this->filter = $filter;
    }


    /**
     * Filter transactions
     * GET api/transactions?limit=40&page=2&account_id=3&type=income
     * POST api/select/filter
     * @param Request $request
     * @param TransactionsRepository $transactionsRepository
     * @return array
     */
    public function transactions(Request $request)
    {
        /**
         * @VP:
         * Not sure how I can access values like this:
         * $this->filter->transactions
         * $this->filter->totals
         * $this->filter->graphTotals
         * because I need to pass $request->get('filter') and I'm not sure how
         * to get that in my constructor without newing up a new Filter.
         */
//        return $this->filter->filterTransactions($request->get('filter'))->transactions;
        return $this->filter->setFilter($request->get('filter'));
//        return $this->filterRepository->filterTransactions($request->get('filter'));
    }
}
