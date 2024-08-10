<?php

namespace App\Providers;

use App\Models\RentedCar;
use App\Observers\RentedCarObserver;
use Illuminate\Support\ServiceProvider;

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
        RentedCar::observe(RentedCarObserver::class);
    }
}
