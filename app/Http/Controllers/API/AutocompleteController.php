<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Transactions\TransactionsRepository;
use Illuminate\Http\Request;

/**
 * Class AutocompleteController
 * @package App\Http\Controllers
 */
class AutocompleteController extends Controller
{

    /**
     * @var TransactionsRepository
     */
    private $transactionsRepository;

    /**
     * Create a new controller instance.
     */
    public function __construct(TransactionsRepository $transactionsRepository)
    {
        $this->middleware('auth');
        $this->transactionsRepository = $transactionsRepository;
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function transaction(Request $request)
    {
        $typing = '%' . $request->get('typing') . '%';
        $transactions = $this->transactionsRepository->autocompleteTransaction($request->get('column'), $typing);

        return $transactions;
    }

}
