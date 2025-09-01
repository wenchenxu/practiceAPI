<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Assignment;

class AssignmentPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // HQ sees all (read-only), managers see their city via controller scope
    }

    public function view(User $user, Assignment $assignment): bool
    {
        return $user->isHQ() || $assignment->city_id === $user->city_id;
    }

    // Creating an assignment == "assign"
    public function create(User $user): bool
    {
        return !$user->isHQ(); // HQ is read-only
    }

    // Updating an assignment (e.g., release) — treat as update
    public function update(User $user, Assignment $assignment): bool
    {
        return !$user->isHQ() && $assignment->city_id === $user->city_id;
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        return !$user->isHQ() && $assignment->city_id === $user->city_id;
    }
}
