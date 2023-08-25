<?php

namespace App\Observers;

use App\Models\Badge;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(User $user): void
    {
        $badge = Badge::query()->where('achievements_number', 0)->first();
        if (! $badge) {
            return;
        }
        $user->badges()->attach($badge);
    }
}
