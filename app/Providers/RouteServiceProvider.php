<?php namespace App\Providers;

use App\Models\Account;
use App\Models\Budget;
use App\Models\FavouriteTransaction;
use App\Models\SavedFilter;
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
	 * @return void
	 */
	public function boot()
	{
		parent::boot();

//		$router->model('accounts', Account::class);

		Route::bind('account', function($id)
		{
			return Account::forCurrentUser()->findOrFail($id);
		});

        Route::bind('budget', function($id)
        {
            return Budget::forCurrentUser()->findOrFail($id);
        });

        Route::bind('transaction', function($id)
        {
            return Transaction::forCurrentUser()->findOrFail($id);
        });

        Route::bind('favouriteTransaction', function($id)
        {
            return FavouriteTransaction::forCurrentUser()->findOrFail($id);
        });

		Route::bind('savedFilter', function($id)
		{
			return SavedFilter::forCurrentUser()->findOrFail($id);
		});
	}

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }
    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }

}
