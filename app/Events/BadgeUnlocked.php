<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BadgeUnlocked
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public string $badge_name;

    public User $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $badge_name, User $user)
    {
        $this->badge_name = $badge_name;
        $this->user = $user;
    }
}
