<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
	// Skip view-namespace setup during CLI commands (e.g., view:clear, cache:clear)
        if ($this->app->runningInConsole()) {
            return;
        }

        if (request()?->is('health')) {
        	return;
    	}
	//
    }
}
