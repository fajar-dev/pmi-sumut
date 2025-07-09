<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SubMenu;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubMenuPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any submenus.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_sub::menu');
    }

    /**
     * Determine whether the user can view the submenu.
     */
    public function view(User $user, SubMenu $subMenu): bool
    {
        return $user->can('view_sub::menu');
    }

    /**
     * Determine whether the user can create a submenu.
     */
    public function create(User $user): bool
    {
        return $user->can('create_sub::menu');
    }

    /**
     * Determine whether the user can update the submenu.
     */
    public function update(User $user, SubMenu $subMenu): bool
    {
        return $user->can('update_sub::menu');
    }

    /**
     * Determine whether the user can delete the submenu.
     */
    public function delete(User $user, SubMenu $subMenu): bool
    {
        return $user->can('delete_sub::menu');
    }

    /**
     * Determine whether the user can bulk delete submenus.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_sub::menu');
    }

    /**
     * Determine whether the user can permanently delete the submenu.
     */
    public function forceDelete(User $user, SubMenu $subMenu): bool
    {
        return $user->can('force_delete_sub::menu');
    }

    /**
     * Determine whether the user can permanently bulk delete submenus.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_sub::menu');
    }

    /**
     * Determine whether the user can restore the submenu.
     */
    public function restore(User $user, SubMenu $subMenu): bool
    {
        return $user->can('restore_sub::menu');
    }

    /**
     * Determine whether the user can bulk restore submenus.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_sub::menu');
    }

    /**
     * Determine whether the user can replicate the submenu.
     */
    public function replicate(User $user, SubMenu $subMenu): bool
    {
        return $user->can('replicate_sub::menu');
    }

    /**
     * Determine whether the user can reorder submenus.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_sub::menu');
    }
}
