<?php

namespace App\Providers;

use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Assignment;
use App\Policies\VehiclePolicy;
use App\Policies\DriverPolicy;
use App\Policies\AssignmentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Vehicle::class    => VehiclePolicy::class,
        Driver::class     => DriverPolicy::class,
        Assignment::class => AssignmentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Handy UI toggle (optional)
        Gate::define('isHQ', fn($user) => $user->role === 'hq');
    }
}
