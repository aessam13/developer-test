<?php

namespace App\Events;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LessonWatched
{
    use Dispatchable;
    use SerializesModels;

    public Lesson $lesson;

    public User $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Lesson $lesson, User $user)
    {
        $this->lesson = $lesson;
        $this->user = $user;
    }
}
