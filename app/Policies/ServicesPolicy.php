<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Services;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicesPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Services');
    }

    public function view(AuthUser $authUser, Services $services): bool
    {
        return $authUser->can('View:Services');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Services');
    }

    public function update(AuthUser $authUser, Services $services): bool
    {
        return $authUser->can('Update:Services');
    }

    public function delete(AuthUser $authUser, Services $services): bool
    {
        return $authUser->can('Delete:Services');
    }

    public function restore(AuthUser $authUser, Services $services): bool
    {
        return $authUser->can('Restore:Services');
    }

    public function forceDelete(AuthUser $authUser, Services $services): bool
    {
        return $authUser->can('ForceDelete:Services');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Services');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Services');
    }

    public function replicate(AuthUser $authUser, Services $services): bool
    {
        return $authUser->can('Replicate:Services');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Services');
    }

}