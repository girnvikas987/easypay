<?php

namespace App\Policies;

use App\Models\Support;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;

class SupportPolicy
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
    public function view(Admin $Admin, Support $support): bool
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
    public function update(Admin $Admin, Support $support): bool
    {
        return $Admin->hasRole(['Admin','SuperAdmin']);
    }

    /**
     * Determine whether the Admin can delete the model.
     */
    public function delete(Admin $Admin, Support $support): bool
    {
        return $Admin->hasRole(['Admin','SuperAdmin']);
    }

    /**
     * Determine whether the Admin can restore the model.
     */
    public function restore(Admin $Admin, Support $support): bool
    {
        return $Admin->hasRole(['Admin','SuperAdmin']);
    }

    /**
     * Determine whether the Admin can permanently delete the model.
     */
    public function forceDelete(Admin $Admin, Support $support): bool
    {
        return $Admin->hasRole(['Admin','SuperAdmin']);
    }
}
