<?php

namespace App\Policies;

use App\Models\User;

class ItemCategoryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }
}
