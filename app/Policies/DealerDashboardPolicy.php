<?php

namespace App\Policies;

use App\Models\User;

class DealerDashboardPolicy
{
    /**
     * Determine whether the user can view the dealer dashboard.
     */
    public function view(User $user): bool
    {
        // On n'autorise que les dealers et les administrateurs
        return $user->hasRole(['dealer', 'admin']) && $user->status === UserStatus::ACTIVE;
    }
}
