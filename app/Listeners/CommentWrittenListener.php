<?php

namespace App\Listeners;

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
     */
    public function handle(object $event)
    {
        $comment_user = $event->comment->user;
        $user_comments_count = $comment_user->comments->count();
        $achievements = Achievement::whereType(AchievementTypes::Comment)->get();

        $action_listener = new ActionListener();
        $action_listener->handle($user_comments_count, $achievements, $comment_user);
    }
}
