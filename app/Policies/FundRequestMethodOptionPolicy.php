<?php

namespace App\Policies;

use App\Models\FundRequestMethodOption;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;

class FundRequestMethodOptionPolicy
{
    /**
     * Determine whether the Admin can view any models.
     */
    public function viewAny(Admin $Admin): bool
    {
        return $Admin->hasRole('Admin');
    }

    /**
     * Determine whether the Admin can view the model.
     */
    public function view(Admin $Admin, FundRequestMethodOption $fundRequestMethodOption): bool
    {
        return $Admin->hasRole('Admin');
    }

    /**
     * Determine whether the Admin can create models.
     */
    public function create(Admin $Admin): bool
    {
        return $Admin->hasRole('Admin');
    }

    /**
     * Determine whether the Admin can update the model.
     */
    public function update(Admin $Admin, FundRequestMethodOption $fundRequestMethodOption): bool
    {
        return $Admin->hasRole('Admin');
    }

    /**
     * Determine whether the Admin can delete the model.
     */
    public function delete(Admin $Admin, FundRequestMethodOption $fundRequestMethodOption): bool
    {
        return $Admin->hasRole('Admin');
    }

    /**
     * Determine whether the Admin can restore the model.
     */
    public function restore(Admin $Admin, FundRequestMethodOption $fundRequestMethodOption): bool
    {
        return $Admin->hasRole('Admin');
    }

    /**
     * Determine whether the Admin can permanently delete the model.
     */
    public function forceDelete(Admin $Admin, FundRequestMethodOption $fundRequestMethodOption): bool
    {
        return $Admin->hasRole('Admin');
    }
}
