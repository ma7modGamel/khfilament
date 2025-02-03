<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ProjectUser;
use App\Models\User;

class ProjectUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ProjectUser');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProjectUser $projectuser): bool
    {
        return $user->checkPermissionTo('view ProjectUser');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ProjectUser');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProjectUser $projectuser): bool
    {
        return $user->checkPermissionTo('update ProjectUser');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjectUser $projectuser): bool
    {
        return $user->checkPermissionTo('delete ProjectUser');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProjectUser $projectuser): bool
    {
        return $user->checkPermissionTo('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProjectUser $projectuser): bool
    {
        return $user->checkPermissionTo('{{ forceDeletePermission }}');
    }
}
