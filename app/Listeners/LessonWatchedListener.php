<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LessonWatchedListener
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
        $user_lessons_watched_count = $event->user->watched()->count();
        $achievements = Achievement::whereType(Achievement::LESSON)->get();

        $action_listener = new ActionListener();
        $action_listener->handle($user_lessons_watched_count, $achievements, $event->user);
    }
}
