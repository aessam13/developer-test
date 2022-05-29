<?php

namespace App\Actions;

use App\Events\AchievementUnlocked;
use App\Models\User;

class UserAchievementAction
{
    public function __invoke(int $action_count, $achievements, User $user)
    {
        foreach ($achievements as $achievement) {
            if ($action_count == $achievement->action_count) {
                $user->achievements()->attach($achievement);
                AchievementUnlocked::dispatch($achievement->title, $user);
            }
        }
    }
}
