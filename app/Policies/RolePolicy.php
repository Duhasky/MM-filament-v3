<?php

namespace App\Policies;

use App\Models\{Role, User};

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->abilities()->contains('admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->abilities()->contains('admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->abilities()->contains('admin');
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        return $user->abilities()->contains('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->abilities()->contains('admin');
    }

    public function abilities(User $user, Role $role): bool
    {
        return $user->abilities()->contains('admin');
    }
}
