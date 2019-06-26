<?php

namespace App\Providers;

use App\Models\Forum\Channel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		\View::composer('forum/*', function ($view) {
			/*$channels = \Cache::rememberForever('channels', function () {
				return Channel::all();
			});*/
			$channels = Channel::all();
			$view->with('channels', $channels);
		});
    }
}
