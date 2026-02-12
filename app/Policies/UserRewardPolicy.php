<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Admin;
use App\Models\UserReward;
use Illuminate\Auth\Access\Response;

class UserRewardPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $user): bool
    {
         return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $user, UserReward $userReward): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $user): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $user, UserReward $userReward): bool
    {
         return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $user, UserReward $userReward): bool
    {
         return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $user, UserReward $userReward): bool
    {
         return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $user, UserReward $userReward): bool
    {
         return $user->hasRole('Admin');
    }
}
