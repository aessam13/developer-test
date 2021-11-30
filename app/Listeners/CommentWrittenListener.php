<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
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
     * @param  object  $event
     * @return AchievementUnlocked
     */
    public function handle($event)
    {
        $comment_user = $event->comment->user;
        $user_comments_count = $comment_user->comments()->count();
        $achievements = Achievement::whereType(Achievement::COMMENT)->get();

        foreach ($achievements as $achievement)
        {
            if($user_comments_count == $achievement->number)
            {
                $comment_user->achievements()->attach($achievement);
                AchievementUnlocked::dispatch($achievement->title, $comment_user);
            }
        }
    }
}
