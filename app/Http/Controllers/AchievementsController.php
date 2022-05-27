<?php

namespace App\Http\Controllers;

use App\Http\Resources\AchievementResource;
use App\Models\User;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        return response()->json([
            'unlocked_achievements' => $user->achievements()->pluck('title'),
            'next_available_achievements' => $user->next_available_achievements() ? $user->next_available_achievements()->pluck('title') : null,
            'current_badge' => !$user->badges->isEmpty() ? $user->badges->last()->title : null,
            'next_badge' => $user->next_badge(),
            'remaining_to_unlock_next_badge' => $user->remaining_to_unlock_next_badge(),
        ]);
    }
}
