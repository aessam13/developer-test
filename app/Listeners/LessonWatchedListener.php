<?php

namespace App\Listeners;

use App\Actions\UserAchievementAction;
use App\Enums\AchievementTypes;
use App\Models\Achievement;

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
    public function handle(object $event)
    {
        $user_lessons_watched_count = $event->user->watched->count();
        $achievements = Achievement::query()->where('type',AchievementTypes::Lesson)->get();

        app()->make(UserAchievementAction::class)($user_lessons_watched_count, $achievements, $event->user);
    }
}
