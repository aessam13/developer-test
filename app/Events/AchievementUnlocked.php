<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AchievementUnlocked
{
    use Dispatchable, SerializesModels;

    public $achievement_name;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $achievement_name, User $user)
    {
        $this->achievement_name = $achievement_name;
        $this->user = $user;
    }
}
