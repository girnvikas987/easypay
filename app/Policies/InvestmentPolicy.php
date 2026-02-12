<?php

namespace App\Policies;

use App\Models\Investment;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;

class InvestmentPolicy
{
    /**
     * Determine whether the Admin can view any models.
     */
    public function viewAny(Admin $Admin): bool
    {
        return $Admin->hasRole(['Admin','SuperAdmin']);
    }

    /**
     * Determine whether the Admin can view the model.
     */
    public function view(Admin $Admin, Investment $investment): bool
    {
        return $Admin->hasRole(['Admin','SuperAdmin']);
    }

    /**
     * Determine whether the Admin can create models.
     */
    public function create(Admin $Admin): bool
    {
        return $Admin->hasRole(['Admin','SuperAdmin']);
    }

    /**
     * Determine whether the Admin can update the model.
     */
    public function update(Admin $Admin, Investment $investment): bool
    {
        return $Admin->hasRole(['Admin','SuperAdmin']);
    }

    /**
     * Determine whether the Admin can delete the model.
     */
    public function delete(Admin $Admin, Investment $investment): bool
    {
        return $Admin->hasRole(['Admin','SuperAdmin']);
    }

    /**
     * Determine whether the Admin can restore the model.
     */
    public function restore(Admin $Admin, Investment $investment): bool
    {
        return $Admin->hasRole(['Admin','SuperAdmin']);
    }

    /**
     * Determine whether the Admin can permanently delete the model.
     */
    public function forceDelete(Admin $Admin, Investment $investment): bool
    {
        return $Admin->hasRole(['Admin','SuperAdmin']);
    }
}
