<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\FormStatusRole;
use App\Models\User;

class FormStatusRolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any FormStatusRole');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FormStatusRole $formstatusrole): bool
    {
        return $user->checkPermissionTo('view FormStatusRole');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create FormStatusRole');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FormStatusRole $formstatusrole): bool
    {
        return $user->checkPermissionTo('update FormStatusRole');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FormStatusRole $formstatusrole): bool
    {
        return $user->checkPermissionTo('delete FormStatusRole');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FormStatusRole $formstatusrole): bool
    {
        return $user->checkPermissionTo('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FormStatusRole $formstatusrole): bool
    {
        return $user->checkPermissionTo('{{ forceDeletePermission }}');
    }
}
