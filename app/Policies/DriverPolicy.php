<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Driver;

class DriverPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Driver $driver): bool
    {
        return $user->isHQ() || $driver->city_id === $user->city_id;
    }

    public function create(User $user): bool
    {
        return !$user->isHQ();
    }

    public function update(User $user, Driver $driver): bool
    {
        return !$user->isHQ() && $driver->city_id === $user->city_id;
    }

    public function delete(User $user, Driver $driver): bool
    {
        return !$user->isHQ() && $driver->city_id === $user->city_id;
    }
}
