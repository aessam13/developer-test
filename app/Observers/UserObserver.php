<?php

namespace App\Observers;

use App\Models\Badge;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  User  $user
     * @return void
     */
    public function created(User $user)
    {
        $badge = Badge::whereNumber(0)->first();
        $user->badges()->attach($badge);
    }
}