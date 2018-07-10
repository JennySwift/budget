<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\Budget;
use App\Models\FavouriteTransaction;
use App\Models\SavedFilter;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
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

        parent::boot();
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
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
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
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
