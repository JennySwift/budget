<?php

namespace App\Providers;

use Validator;
use App\Models\Budget;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('accepted_email', function ($attribute, $value, $parameters) {
            $accepted_emails = [
                //enter emails here
                'cheezyspaghetti@gmail.com',
                'cheezyspaghetti@optusnet.com.au',
                'nihantanu@gmail.com'
            ];
            $is_accepted = in_array($value, $accepted_emails);
            return $is_accepted;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
