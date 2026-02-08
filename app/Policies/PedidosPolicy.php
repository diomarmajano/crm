<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Pedidos;
use Illuminate\Auth\Access\HandlesAuthorization;

class PedidosPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Pedidos');
    }

    public function view(AuthUser $authUser, Pedidos $pedidos): bool
    {
        return $authUser->can('View:Pedidos');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Pedidos');
    }

    public function update(AuthUser $authUser, Pedidos $pedidos): bool
    {
        return $authUser->can('Update:Pedidos');
    }

    public function delete(AuthUser $authUser, Pedidos $pedidos): bool
    {
        return $authUser->can('Delete:Pedidos');
    }

    public function restore(AuthUser $authUser, Pedidos $pedidos): bool
    {
        return $authUser->can('Restore:Pedidos');
    }

    public function forceDelete(AuthUser $authUser, Pedidos $pedidos): bool
    {
        return $authUser->can('ForceDelete:Pedidos');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Pedidos');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Pedidos');
    }

    public function replicate(AuthUser $authUser, Pedidos $pedidos): bool
    {
        return $authUser->can('Replicate:Pedidos');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Pedidos');
    }

}