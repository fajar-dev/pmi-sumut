<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Service;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any services.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_service');
    }

    /**
     * Determine whether the user can view the service.
     */
    public function view(User $user, Service $service): bool
    {
        return $user->can('view_service');
    }

    /**
     * Determine whether the user can create a service.
     */
    public function create(User $user): bool
    {
        return $user->can('create_service');
    }

    /**
     * Determine whether the user can update the service.
     */
    public function update(User $user, Service $service): bool
    {
        return $user->can('update_service');
    }

    /**
     * Determine whether the user can delete the service.
     */
    public function delete(User $user, Service $service): bool
    {
        return $user->can('delete_service');
    }

    /**
     * Determine whether the user can bulk delete services.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_service');
    }

    /**
     * Determine whether the user can permanently delete the service.
     */
    public function forceDelete(User $user, Service $service): bool
    {
        return $user->can('force_delete_service');
    }

    /**
     * Determine whether the user can permanently bulk delete services.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_service');
    }

    /**
     * Determine whether the user can restore the service.
     */
    public function restore(User $user, Service $service): bool
    {
        return $user->can('restore_service');
    }

    /**
     * Determine whether the user can bulk restore services.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_service');
    }

    /**
     * Determine whether the user can replicate the service.
     */
    public function replicate(User $user, Service $service): bool
    {
        return $user->can('replicate_service');
    }

    /**
     * Determine whether the user can reorder services.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_service');
    }
}
