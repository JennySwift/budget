<?php namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Color;
use App\Repositories\Tags\TagsRepository;
use App\Repositories\Transactions\FilterRepository;
use App\Totals\TotalsService;
use Illuminate\Support\Facades\Auth;
use JavaScript;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

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
	public function index(FilterRepository $filterRepository, TagsRepository $tagsRepository, TotalsService $totalsService)
	{
        $filter = [
            "budget" => [],
            "total" => "",
            "types" => [],
            "accounts" => [],
            "single_date" => "",
            "from_date" => "",
            "to_date" => "",
            "description" => "",
            "merchant" => "",
            "tags" => [],
            "reconciled" => "any",
            "offset" => 0,
            "num_to_fetch" => 20
        ];

        JavaScript::put([
            //It wouldn't work if I named it 'transactions', or 'totals'
            'filter_response' => $filterRepository->filterTransactions($filter),
            'totals_response' => $totalsService->getBasicAndBudgetTotals(),
            'accounts_response' => Account::getAccounts(),
            'tags_response' => $tagsRepository->getTags(),
            'colors_response' => Color::getColors(),
            'me' => Auth::user(),
            'env' => app()->env
        ]);

		return view('home');
	}

}
