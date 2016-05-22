<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\TransactionTransformer;
use App\Repositories\Filters\FilterQueryRepository;
use App\Repositories\Filters\FilterTotalsRepository;
use App\Repositories\Filters\GraphsRepository;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Auth;

/**
 * Class FilterController
 * @package App\Http\Controllers
 */
class FilterController extends Controller
{

    /**
     * @var FilterQueryRepository
     */
    private $filterQueryRepository;
    /**
     * @var FilterTotalsRepository
     */
    private $filterTotalsRepository;
    /**
     * @var GraphsRepository
     */
    private $graphsRepository;

    /**
     * Create a new controller instance.
     * @param FilterQueryRepository $filterQueryRepository
     * @param FilterTotalsRepository $filterTotalsRepository
     * @param GraphsRepository $graphsRepository
     */
    public function __construct(FilterQueryRepository $filterQueryRepository, FilterTotalsRepository $filterTotalsRepository, GraphsRepository $graphsRepository)
    {
        $this->filterQueryRepository = $filterQueryRepository;
        $this->filterTotalsRepository = $filterTotalsRepository;
        $this->graphsRepository = $graphsRepository;
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
        $filter = array_merge(Config::get('filters.defaults'), $request->get('filter'));
        $query = $this->filterQueryRepository->buildQuery($filter);

        $transactions = $query->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->skip($filter['offset'])
            ->take($filter['numToFetch'])
            ->with('budgets')
            ->with('assignedBudgets')
            ->with('account')
            ->get();

        if ($request->get('filter')['invalidAllocation'] === 'true') {
            $transactions = $transactions->filter(function ($transaction) {
                return $transaction->validAllocation === false;
            });
        }

        $transactions = $this->transform($this->createCollection($transactions, new TransactionTransformer))['data'];

        return response($transactions, Response::HTTP_OK);
    }

    /**
     * POST api/filter/basicTotals
     * @param Request $request
     * @return array
     */
    public function basicTotals(Request $request)
    {
        $filter = array_merge(Config::get('filters.defaults'), $request->get('filter'));
        $query = $this->filterQueryRepository->buildQuery($filter);
        $queryForCalculatingBalance = $this->filterQueryRepository->buildQueryForCalculatingBalance($filter);
        
        return $this->filterTotalsRepository->getFilterTotals($query, $queryForCalculatingBalance)->toArray();
    }

    /**
     * POST api/filter/graphTotals
     * @param Request $request
     * @return array
     */
    public function graphTotals(Request $request)
    {
        $filter = array_merge(Config::get('filters.defaults'), $request->get('filter'));
        $query = $this->filterQueryRepository->buildQuery($filter);
        $queryForCalculatingBalance = $this->filterQueryRepository->buildQueryForCalculatingBalance($filter);

        return $this->graphsRepository->getGraphTotals($query, $queryForCalculatingBalance);
    }
}
