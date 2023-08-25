<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use App\Models\Badge;

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
     */
    public function handle(object $event): void
    {
        $achievement_count = $event->user->achievements->count();
        Badge::all()->each(function ($badge) use ($achievement_count, $event) {
            if ($achievement_count == $badge->achievements_number) {
                $event->user->badges()->attach($badge);
                BadgeUnlocked::dispatch($badge->title, $event->user);
            }
        });
    }
}
