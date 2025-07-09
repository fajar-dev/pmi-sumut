<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Slider;
use Illuminate\Auth\Access\HandlesAuthorization;

class SliderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any sliders.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_slider');
    }

    /**
     * Determine whether the user can view the slider.
     */
    public function view(User $user, Slider $slider): bool
    {
        return $user->can('view_slider');
    }

    /**
     * Determine whether the user can create a slider.
     */
    public function create(User $user): bool
    {
        return $user->can('create_slider');
    }

    /**
     * Determine whether the user can update the slider.
     */
    public function update(User $user, Slider $slider): bool
    {
        return $user->can('update_slider');
    }

    /**
     * Determine whether the user can delete the slider.
     */
    public function delete(User $user, Slider $slider): bool
    {
        return $user->can('delete_slider');
    }

    /**
     * Determine whether the user can bulk delete sliders.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_slider');
    }

    /**
     * Determine whether the user can permanently delete the slider.
     */
    public function forceDelete(User $user, Slider $slider): bool
    {
        return $user->can('force_delete_slider');
    }

    /**
     * Determine whether the user can permanently bulk delete sliders.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_slider');
    }

    /**
     * Determine whether the user can restore the slider.
     */
    public function restore(User $user, Slider $slider): bool
    {
        return $user->can('restore_slider');
    }

    /**
     * Determine whether the user can bulk restore sliders.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_slider');
    }

    /**
     * Determine whether the user can replicate the slider.
     */
    public function replicate(User $user, Slider $slider): bool
    {
        return $user->can('replicate_slider');
    }

    /**
     * Determine whether the user can reorder sliders.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_slider');
    }
}
