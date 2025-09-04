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
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;


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
        // Gate::define('isHQ', fn($user) => $user->role === 'hq');
        RateLimiter::for('login', function (Request $request) {
            $key = 'login:' . strtolower((string) $request->input('username')) . '|' . $request->ip();
            return [
                Limit::perMinute(5)->by($key),
            ];
        });
    }
}
