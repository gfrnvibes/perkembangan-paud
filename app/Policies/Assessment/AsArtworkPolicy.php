<?php

declare(strict_types=1);

namespace App\Policies\Assessment;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Assessment\AsArtwork;
use Illuminate\Auth\Access\HandlesAuthorization;

class AsArtworkPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AsArtwork');
    }

    public function view(AuthUser $authUser, AsArtwork $asArtwork): bool
    {
        return $authUser->can('View:AsArtwork');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AsArtwork');
    }

    public function update(AuthUser $authUser, AsArtwork $asArtwork): bool
    {
        return $authUser->can('Update:AsArtwork');
    }

    public function delete(AuthUser $authUser, AsArtwork $asArtwork): bool
    {
        return $authUser->can('Delete:AsArtwork');
    }

    public function restore(AuthUser $authUser, AsArtwork $asArtwork): bool
    {
        return $authUser->can('Restore:AsArtwork');
    }

    public function forceDelete(AuthUser $authUser, AsArtwork $asArtwork): bool
    {
        return $authUser->can('ForceDelete:AsArtwork');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AsArtwork');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AsArtwork');
    }

    public function replicate(AuthUser $authUser, AsArtwork $asArtwork): bool
    {
        return $authUser->can('Replicate:AsArtwork');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AsArtwork');
    }

}