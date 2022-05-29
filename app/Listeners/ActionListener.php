<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Models\User;

class ActionListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(int $action_count, $achievements, User $user)
    {
        foreach ($achievements as $achievement) {
            if ($action_count == $achievement->action_count) {
                $user->achievements()->attach($achievement);
                AchievementUnlocked::dispatch($achievement->title, $user);
            }
        }
    }
}
