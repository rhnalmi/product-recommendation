<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SubPart; // Add this
use App\Observers\SubPartObserver; // Add this

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
        //
        SubPart::observe(SubPartObserver::class); // Add this line
    }
}
