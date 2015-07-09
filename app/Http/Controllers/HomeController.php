<?php namespace App\Http\Controllers;

use App\Repositories\Transactions\TransactionsRepository;
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
	public function index(TransactionsRepository $transactionsRepository)
	{
        $filter = [
            "budget" => "all",
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
            //It wouldn't work if I named it 'transactions'
            'filter_response' => $transactionsRepository->filterTransactions($filter),
            'me' => Auth::user()
        ]);

		return view('home');
	}

}
