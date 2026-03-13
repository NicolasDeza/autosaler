<?php

namespace App\Providers;

use App\Http\Controllers\DealerDashboardController;
use App\Policies\DealerDashboardPolicy;
use App\Policies\VehicleAdPolicy;
use App\Models\VehicleAd;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        DealerDashboardController::class => DealerDashboardPolicy::class,
        VehicleAd::class => VehicleAdPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
