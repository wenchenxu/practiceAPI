<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    // List page
    public function viewAny(User $user): bool
    {
        return true; // both HQ and city managers can list
    }

    // Show a single vehicle
    public function view(User $user, Vehicle $vehicle): bool
    {
        return $user->isHQ() || $vehicle->city_id === $user->city_id;
    }

    // Create (HQ is read-only by your requirement)
    public function create(User $user): bool
    {
        // return !$user->isHQ();
        return $user->role === 'city_manager';
    }

    // Update (HQ is read-only)
    public function update(User $user, Vehicle $vehicle): bool
    {
        return !$user->isHQ() && $vehicle->city_id === $user->city_id;
    }

    // Delete (HQ is read-only)
    public function delete(User $user, Vehicle $vehicle): bool
    {
        return !$user->isHQ() && $vehicle->city_id === $user->city_id;
    }
}
