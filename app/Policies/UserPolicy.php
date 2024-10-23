<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->abilities()->contains('user_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->abilities()->contains('user_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->abilities()->contains('user_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if (!$user->hierarchy($model->id)) {
            return false;
        }

        return $user->abilities()->contains('user_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }

        if (!$user->hierarchy($model->id)) {
            return false;
        }

        return $user->abilities()->contains('user_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }

        if (!$user->hierarchy($model->id)) {
            return false;
        }

        return $user->abilities()->contains('user_delete');
    }
}