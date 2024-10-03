<?php

namespace App\Policies;

use App\Models\User;

class ItemExitPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }
}
