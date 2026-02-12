<?php

namespace App\Policies;

use App\Models\LevelCommision;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;

class LevelCommisionPolicy
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
    public function view(Admin $Admin, LevelCommision $levelCommision): bool
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
    public function update(Admin $Admin, LevelCommision $levelCommision): bool
    {
        return $Admin->hasRole('Admin');
    }

    /**
     * Determine whether the Admin can delete the model.
     */
    public function delete(Admin $Admin, LevelCommision $levelCommision): bool
    {
        return $Admin->hasRole('Admin');
    }

    /**
     * Determine whether the Admin can restore the model.
     */
    public function restore(Admin $Admin, LevelCommision $levelCommision): bool
    {
        return $Admin->hasRole('Admin');
    }

    /**
     * Determine whether the Admin can permanently delete the model.
     */
    public function forceDelete(Admin $Admin, LevelCommision $levelCommision): bool
    {
        return $Admin->hasRole('Admin');
    }
}
