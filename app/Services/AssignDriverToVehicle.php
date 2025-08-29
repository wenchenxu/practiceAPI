<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssignDriverToVehicle
{
    public function handle(Vehicle $vehicle, Driver $driver, ?Carbon $when = null, ?string $notes = null): Assignment
    {
        return DB::transaction(function () use ($vehicle, $driver, $when, $notes) {
            // IMPORTANT: avoid latestOfMany() here; use a simple WHERE + lock
            $vehicleHasActive = $vehicle->assignments()
                ->whereNull('released_at')
                ->lockForUpdate()
                ->exists();

            if ($vehicleHasActive) {
                throw new \RuntimeException('Vehicle already has an active assignment.');
            }

            $driverHasActive = $driver->assignments()
                ->whereNull('released_at')
                ->lockForUpdate()
                ->exists();

            if ($driverHasActive) {
                throw new \RuntimeException('Driver already has an active vehicle.');
            }

            // Create the new active assignment
            return $vehicle->assignments()->create([
                'driver_id'   => $driver->id,
                'city_id'     => $vehicle->city_id,
                'assigned_at' => $when?->toDateTimeString() ?? now(),
                'notes'       => $notes,
            ]);
        });
    }
}
