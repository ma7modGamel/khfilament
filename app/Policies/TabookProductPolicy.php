<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TabookProduct;
use App\Models\User;

class TabookProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any TabookProduct');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TabookProduct $tabookproduct): bool
    {
        return $user->checkPermissionTo('view TabookProduct');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create TabookProduct');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TabookProduct $tabookproduct): bool
    {
        return $user->checkPermissionTo('update TabookProduct');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TabookProduct $tabookproduct): bool
    {
        return $user->checkPermissionTo('delete TabookProduct');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TabookProduct $tabookproduct): bool
    {
        return $user->checkPermissionTo('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TabookProduct $tabookproduct): bool
    {
        return $user->checkPermissionTo('{{ forceDeletePermission }}');
    }
}
