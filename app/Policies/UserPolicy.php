<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Only admins can update users.
     * Admins can only update non-admins, except themselves.
     */
    public function update(User $auth, User $target): bool
    {
        if (! $auth->isAdmin()) return false;

        // Admins can edit themselves
        if ($auth->id === $target->id) return true;

        // Admins cannot edit other admins (prevents privilege escalation)
        return $target->role !== 'admin';
    }

    /**
     * Only admins can delete users, and cannot delete themselves
     * or other admins.
     */
    public function delete(User $auth, User $target): bool
    {
        if (! $auth->isAdmin()) return false;
        if ($auth->id === $target->id) return false;
        return $target->role !== 'admin';
    }
}
