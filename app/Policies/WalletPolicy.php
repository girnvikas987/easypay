<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Wallet;
use Illuminate\Auth\Access\Response;

class WalletPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $user): bool
    {
          return $user->hasRole(['Admin','SuperAdmin']);
    }
    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $user, Wallet $wallet): bool
    {
          return $user->hasRole(['Admin','SuperAdmin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $user): bool
    {
          return $user->hasRole(['Admin','SuperAdmin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $user, Wallet $wallet): bool
    {
          return $user->hasRole(['Admin','SuperAdmin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $user, Wallet $wallet): bool
    {
          return $user->hasRole(['Admin','SuperAdmin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $user, Wallet $wallet): bool
    {
          return $user->hasRole(['Admin','SuperAdmin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $user, Wallet $wallet): bool
    {
           return $user->hasRole(['Admin','SuperAdmin']);
    }
}
