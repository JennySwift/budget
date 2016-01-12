<?php namespace App\Providers;

use App\Models\Account;
use App\Models\Budget;
use App\Models\FavouriteTransaction;
use App\Models\Tag;
use App\Models\Transaction;
use App\Traits\ForCurrentUserTrait;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider {

    use ForCurrentUserTrait;

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		parent::boot($router);

//		$router->model('accounts', Account::class);

		Route::bind('accounts', function($id)
		{
			return Account::forCurrentUser()->findOrFail($id);
		});

        Route::bind('budgets', function($id)
        {
            return Budget::forCurrentUser()->findOrFail($id);
        });

        Route::bind('transactions', function($id)
        {
            return Transaction::forCurrentUser()->findOrFail($id);
        });

        Route::bind('favouriteTransactions', function($id)
        {
            return FavouriteTransaction::forCurrentUser()->findOrFail($id);
        });
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group(['namespace' => $this->namespace], function($router)
		{
			require app_path('Http/routes.php');
		});
	}

}
