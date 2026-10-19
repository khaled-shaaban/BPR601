<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Report $report): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->isRepresentative()) {
            return $report->authority_id === $user->authority_id || $report->user_id === $user->id;
        }

        return $report->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        // Administrators manage reports; citizens and representatives can file them.
        return ! $user->isAdmin();
    }

    public function update(User $user, Report $report): bool
    {
        return $user->isAdmin() || $report->user_id === $user->id;
    }

    public function delete(User $user, Report $report): bool
    {
        return $user->isAdmin() || $report->user_id === $user->id;
    }

    /**
     * Change a report's status: admins (any authority) or the authority's representative.
     */
    public function updateStatus(User $user, Report $report): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isRepresentative() && $report->authority_id === $user->authority_id;
    }

    /**
     * Add a representative note (super admins bypass via Gate::before).
     */
    public function addRepresentativeNote(User $user, Report $report): bool
    {
        return $user->isRepresentative() && $report->authority_id === $user->authority_id;
    }
}
