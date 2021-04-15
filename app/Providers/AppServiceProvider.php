<?php

namespace App\Providers;

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
        //Repos
        $this->app->bind(
            'App\Repositories\Interfaces\TripRepositoryInterface',
            'App\Repositories\TripRepository'
        );
        $this->app->bind(
            'App\Repositories\Interfaces\ReservationRepositoryInterface',
            'App\Repositories\ReservationRepository'
        );
        //Services
        $this->app->bind(
            'App\Services\Interfaces\TripServiceInterface',
            'App\Services\TripService'
        );

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
