<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TotalsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('remaining-balance', function(){
            return $this->app->make('App\Models\Totals\RemainingBalance');
        });
    }
}
