<?php

namespace Tests\Feature;

use App\Enums\AchievementTypes;
use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Listeners\LessonWatchedListener;
use App\Models\Achievement;
use App\Models\Lesson;
use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LessonWatchedAchievementTest extends TestCase
{
    /** @var User */
    public User $user;

    /** @var Lesson */
    public Lesson $lesson;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->lesson = Lesson::factory()->create();

        $this->seed(AchievementSeeder::class);
    }

    public function test_user_has_lesson_but_did_not_watch_it()
    {
        Event::fake();

        $this->user->lessons()->attach($this->lesson, ['watched' => false]);

        $this->assertDatabaseMissing(
            'achievement_user',
            [
                'user_id' => $this->user->id,
            ]
        );

        Event::assertNotDispatched(AchievementUnlocked::class);
    }

    public function test_user_has_no_lessons()
    {
        Event::fake();

        $this->assertDatabaseMissing(
            'achievement_user',
            [
                'user_id' => $this->user->id,
            ]
        );

        Event::assertNotDispatched(AchievementUnlocked::class);
    }

    public function test_first_lesson_achievement()
    {
        Event::fake();

        $this->user->lessons()->attach($this->lesson, ['watched' => true]);

        $first_lesson_achievement = Achievement::query()->where('action_count', 1)
            ->where('type', AchievementTypes::Lesson)
            ->first();

        $lesson_watched_event = new LessonWatched($this->lesson, $this->user);
        $lesson_watched_listener = new LessonWatchedListener();
        $lesson_watched_listener->handle($lesson_watched_event);

        $this->assertDatabaseHas(
            'achievement_user',
            [
                'achievement_id' => $first_lesson_achievement->id,
                'user_id' => $this->user->id,
            ]
        );

        Event::assertDispatched(function (AchievementUnlocked $event) use ($first_lesson_achievement) {
            return $event->user->id == $this->user->id && $event->achievement_name == $first_lesson_achievement->title;
        });
    }

    public function test_user_has_two_lessons_so_no_achievement_unlocked()
    {
        Event::fake();

        $new_lesson = Lesson::factory()->create();

        $this->user->lessons()->attach($this->lesson, ['watched' => true]);
        $this->user->lessons()->attach($new_lesson, ['watched' => true]);

        $first_lesson_achievement = Achievement::query()->where('action_count', 1)
            ->where('type', AchievementTypes::Lesson)
            ->first();

        $this->user->achievements()->attach($first_lesson_achievement);

        $fifth_lessons_achievement = Achievement::query()->where('action_count', 5)
            ->where('type', AchievementTypes::Lesson)
            ->first();

        /** @var Lesson $new_lesson */
        $lesson_watched_event = new LessonWatched($new_lesson, $this->user);
        $lesson_watched_listener = new LessonWatchedListener();
        $lesson_watched_listener->handle($lesson_watched_event);

        $this->assertDatabaseHas(
            'achievement_user',
            [
                'achievement_id' => $first_lesson_achievement->id,
                'user_id' => $this->user->id,
            ]
        );

        $this->assertDatabaseMissing(
            'achievement_user',
            [
                'achievement_id' => $fifth_lessons_achievement->id,
                'user_id' => $this->user->id,
            ]
        );

        Event::assertNotDispatched(AchievementUnlocked::class);
    }

    public function test_fifth_lesson_achievement()
    {
        Event::fake();

        $lessons = Lesson::factory()->count(5)->create();

        foreach ($lessons as $lesson) {
            $this->user->lessons()->attach($lesson, ['watched' => true]);
        }

        $first_lesson_achievement = Achievement::query()->where('action_count', 1)
            ->where('type', AchievementTypes::Lesson)
            ->first();
        $this->user->achievements()->attach($first_lesson_achievement);

        $fifth_lessons_achievement = Achievement::query()->where('action_count', 5)
            ->where('type', AchievementTypes::Lesson)
            ->first();

        /** @var Lesson $new_lesson */
        $lesson_watched_event = new LessonWatched($lessons->last(), $this->user);
        $lesson_watched_listener = new LessonWatchedListener();
        $lesson_watched_listener->handle($lesson_watched_event);

        $this->assertDatabaseHas(
            'achievement_user',
            [
                'achievement_id' => $fifth_lessons_achievement->id,
                'user_id' => $this->user->id,
            ]
        );

        $this->assertDatabaseHas(
            'achievement_user',
            [
                'achievement_id' => $first_lesson_achievement->id,
                'user_id' => $this->user->id,
            ]
        );

        Event::assertDispatched(function (AchievementUnlocked $event) use ($fifth_lessons_achievement) {
            return $event->user->id == $this->user->id && $event->achievement_name == $fifth_lessons_achievement->title;
        });
    }
}
