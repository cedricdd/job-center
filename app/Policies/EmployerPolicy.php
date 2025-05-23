<?php

namespace App\Policies;

use App\Models\User;
use App\Models\employer;
use Illuminate\Auth\Access\Response;

class EmployerPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Employer $employer): bool
    {
        return $employer->user->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, Employer $employer): bool
    {
        return $employer->user->is($user);
    }
}
