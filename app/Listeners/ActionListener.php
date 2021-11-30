<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
     * @param  object  $event
     * @return void
     */
    public function handle($action_count, $achievements, $user)
    {
        foreach ($achievements as $achievement)
        {
            if($action_count == $achievement->number)
            {
                $user->achievements()->attach($achievement);
                AchievementUnlocked::dispatch($achievement->title, $user);
            }
        }
    }
}
