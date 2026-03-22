<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VehicleAd;
use App\UserStatus;

class VehicleAdPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, VehicleAd $vehicleAd): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['dealer', 'admin']) && $user->status === UserStatus::ACTIVE;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VehicleAd $vehicleAd): bool
    {
        return $user->hasRole('admin') && $user->status === UserStatus::ACTIVE || ($user->hasRole('dealer') && $user->id == $vehicleAd->user_id && $user->status === UserStatus::ACTIVE);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VehicleAd $vehicleAd): bool
    {
        return $user->hasRole('admin') && $user->status === UserStatus::ACTIVE || ($user->hasRole('dealer') && $user->id == $vehicleAd->user_id && $user->status === UserStatus::ACTIVE);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, VehicleAd $vehicleAd): bool
    {
        return $user->hasRole('admin') && $user->status === UserStatus::ACTIVE;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, VehicleAd $vehicleAd): bool
    {
        return $user->hasRole('admin') && $user->status === UserStatus::ACTIVE;
    }
}
