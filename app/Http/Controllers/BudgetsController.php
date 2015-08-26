<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Repositories\Tags\TagsRepository;
use App\Services\TotalsService;
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

}
