<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GeneralPagePolicy
{
    use HandlesAuthorization;

    public function page(User $user): bool
    {
        return $user->can('page_generalPage');
    }
}
