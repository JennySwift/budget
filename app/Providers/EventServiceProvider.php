<?php namespace App\Providers;

use App\Events\TransactionWasCreated;
use App\Events\TransactionWasUpdated;
use App\Listeners\UpdateSavingsListener;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		TransactionWasCreated::class => [
			UpdateSavingsListener::class,
		],
        TransactionWasUpdated::class => [
            UpdateSavingsListener::class,
        ],
	];

	/**
	 * Register any other events for your application.
	 *
	 * @return void
	 */
	public function boot()
	{
		parent::boot();

		//
	}

}
