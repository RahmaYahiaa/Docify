<?php

namespace App\Providers;

use App\Models\Prescription;
use App\Observers\Prescription\PrescriptionObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Broadcast::routes(['middleware' => ['auth:sanctum']]);
        Prescription::observe(PrescriptionObserver::class);
        Builder::macro('macroPaginate', function () {
            /** @var Builder $this */
            $perPage = Request::query('per_page', 10);

            return $this->paginate($perPage);
        });
    }
}
