<?php

declare(strict_types=1);

namespace App\Policies\Assessment;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Assessment\AsChecklist;
use Illuminate\Auth\Access\HandlesAuthorization;

class AsChecklistPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AsChecklist');
    }

    public function view(AuthUser $authUser, AsChecklist $asChecklist): bool
    {
        return $authUser->can('View:AsChecklist');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AsChecklist');
    }

    public function update(AuthUser $authUser, AsChecklist $asChecklist): bool
    {
        return $authUser->can('Update:AsChecklist');
    }

    public function delete(AuthUser $authUser, AsChecklist $asChecklist): bool
    {
        return $authUser->can('Delete:AsChecklist');
    }

    public function restore(AuthUser $authUser, AsChecklist $asChecklist): bool
    {
        return $authUser->can('Restore:AsChecklist');
    }

    public function forceDelete(AuthUser $authUser, AsChecklist $asChecklist): bool
    {
        return $authUser->can('ForceDelete:AsChecklist');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AsChecklist');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AsChecklist');
    }

    public function replicate(AuthUser $authUser, AsChecklist $asChecklist): bool
    {
        return $authUser->can('Replicate:AsChecklist');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AsChecklist');
    }

}