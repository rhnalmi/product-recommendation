<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SubPart; // Add this
use App\Observers\SubPartObserver; // Add this
use App\Models\SalesOrder;
use App\Observers\SalesOrderObserver;
use App\Models\DeliveryOrder;
use App\Observers\DeliveryOrderObserver;
use App\Models\ProductReturn;
use App\Observers\ProductReturnObserver;

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

        // --- TAMBAHKAN INI ---
    SalesOrder::observe(SalesOrderObserver::class);
    DeliveryOrder::observe(DeliveryOrderObserver::class);
    ProductReturn::observe(ProductReturnObserver::class);
    // --------------------
    }
}
