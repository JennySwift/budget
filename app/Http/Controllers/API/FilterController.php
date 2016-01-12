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
        $this->filter = $filter;
    }


    /**
     * Filter transactions
     * GET api/transactions?limit=40&page=2&account_id=3&type=income
     * POST api/filter/transactions
     * @param Request $request
     * @return array
     */
    public function transactions(Request $request)
    {
        return $this->filter->getTransactions($request->get('filter'));
    }

    /**
     * POST api/filter/basicTotals
     * @param Request $request
     * @return array
     */
    public function basicTotals(Request $request)
    {
        return $this->filter->getBasicTotals($request->get('filter'));
    }

    /**
     * POST api/filter/graphTotals
     * @param Request $request
     * @return array
     */
    public function graphTotals(Request $request)
    {
        return $this->filter->getGraphTotals($request->get('filter'));
    }
}
