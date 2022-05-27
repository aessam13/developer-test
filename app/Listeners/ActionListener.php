<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;

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
    public function handle($action_count, $achievements, $user)
    {
        foreach ($achievements as $achievement) {
            if ($action_count == $achievement->number) {
                $user->achievements()->attach($achievement);
                AchievementUnlocked::dispatch($achievement->title, $user);
            }
        }
    }
}
