<?php

namespace App\Policies;

use App\Models\User;

class SubmissionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }


    public function edit(User $user): bool
    {
        return $user->isAdmin();
    }

    public function approve(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function reject(User $user): bool
    {
        return $user->isSuperAdmin();
    }
}
