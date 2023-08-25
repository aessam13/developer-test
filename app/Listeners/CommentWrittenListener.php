<?php

namespace App\Listeners;

use App\Actions\UserAchievementAction;
use App\Enums\AchievementTypes;
use App\Models\Achievement;

class CommentWrittenListener
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
     * @param object $event
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(object $event): void
    {
        $comment_user = $event->comment->user;
        $user_comments_count = $comment_user->comments->count();
        $achievements = Achievement::query()->where('type', AchievementTypes::Comment)->get();

        app()->make(UserAchievementAction::class)($user_comments_count, $achievements, $comment_user);
    }
}
