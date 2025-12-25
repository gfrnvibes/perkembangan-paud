<?php

declare(strict_types=1);

namespace App\Policies\Master;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Master\CpElement;
use Illuminate\Auth\Access\HandlesAuthorization;

class CpElementPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CpElement');
    }

    public function view(AuthUser $authUser, CpElement $cpElement): bool
    {
        return $authUser->can('View:CpElement');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CpElement');
    }

    public function update(AuthUser $authUser, CpElement $cpElement): bool
    {
        return $authUser->can('Update:CpElement');
    }

    public function delete(AuthUser $authUser, CpElement $cpElement): bool
    {
        return $authUser->can('Delete:CpElement');
    }

    public function restore(AuthUser $authUser, CpElement $cpElement): bool
    {
        return $authUser->can('Restore:CpElement');
    }

    public function forceDelete(AuthUser $authUser, CpElement $cpElement): bool
    {
        return $authUser->can('ForceDelete:CpElement');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CpElement');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CpElement');
    }

    public function replicate(AuthUser $authUser, CpElement $cpElement): bool
    {
        return $authUser->can('Replicate:CpElement');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CpElement');
    }

}