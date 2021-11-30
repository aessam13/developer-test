<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use App\Models\Badge;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AchievementUnlockedListener
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
    public function handle($event)
    {
        $achievement_count = $event->user->achievements()->count();
        $badges = Badge::all();

        foreach ($badges as $badge)
        {
            if($achievement_count == $badge->number)
            {
                $event->user->badges()->attach($badge);
                BadgeUnlocked::dispatch($badge->title, $event->user);
            }
        }


    }
}
