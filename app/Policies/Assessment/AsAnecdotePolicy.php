<?php

declare(strict_types=1);

namespace App\Policies\Assessment;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Assessment\AsAnecdote;
use Illuminate\Auth\Access\HandlesAuthorization;

class AsAnecdotePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AsAnecdote');
    }

    public function view(AuthUser $authUser, AsAnecdote $asAnecdote): bool
    {
        return $authUser->can('View:AsAnecdote');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AsAnecdote');
    }

    public function update(AuthUser $authUser, AsAnecdote $asAnecdote): bool
    {
        return $authUser->can('Update:AsAnecdote');
    }

    public function delete(AuthUser $authUser, AsAnecdote $asAnecdote): bool
    {
        return $authUser->can('Delete:AsAnecdote');
    }

    public function restore(AuthUser $authUser, AsAnecdote $asAnecdote): bool
    {
        return $authUser->can('Restore:AsAnecdote');
    }

    public function forceDelete(AuthUser $authUser, AsAnecdote $asAnecdote): bool
    {
        return $authUser->can('ForceDelete:AsAnecdote');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AsAnecdote');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AsAnecdote');
    }

    public function replicate(AuthUser $authUser, AsAnecdote $asAnecdote): bool
    {
        return $authUser->can('Replicate:AsAnecdote');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AsAnecdote');
    }

}