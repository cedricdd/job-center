<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;

class JobPolicy
{
    /**
     * Determine whether the user can edit the model.
     */
    public function update(User $user, Job $job): bool
    {
        return $job->employer->user->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, Job $job): bool
    {
        return $job->employer->user->is($user);
    }
}
