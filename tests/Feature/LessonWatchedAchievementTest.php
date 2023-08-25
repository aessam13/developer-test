<?php

namespace Tests\Feature;

use App\Enums\AchievementTypes;
use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Models\Lesson;
use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Tests\TestCase;

class LessonWatchedAchievementTest extends TestCase
{
    public User $user;

    public Lesson $lesson;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->lesson = Lesson::factory()->create();

        $this->seed(AchievementSeeder::class);
    }

    public function test_user_has_lesson_but_did_not_watch_it()
    {
        $this->user->lessons()->attach($this->lesson, ['watched' => false]);

        LessonWatched::dispatch($this->lesson, $this->user);

        $this->assertDatabaseMissing(
            'achievement_user',
            [
                'user_id' => $this->user->id,
            ]
        );
    }

    public function test_user_has_no_lessons()
    {
        LessonWatched::dispatch($this->lesson, $this->user);

        $this->assertDatabaseMissing(
            'achievement_user',
            [
                'user_id' => $this->user->id,
            ]
        );
    }

    public function test_first_lesson_achievement()
    {
        $this->user->lessons()->attach($this->lesson, ['watched' => true]);

        $first_lesson_achievement = Achievement::query()->where('action_count', 1)
            ->where('type', AchievementTypes::Lesson)
            ->first();

        LessonWatched::dispatch($this->lesson, $this->user);

        $this->assertDatabaseHas(
            'achievement_user',
            [
                'achievement_id' => $first_lesson_achievement->id,
                'user_id' => $this->user->id,
            ]
        );
    }

    public function test_user_has_two_lessons_so_no_achievement_unlocked()
    {
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

        /* @var Lesson $new_lesson */
        LessonWatched::dispatch($new_lesson, $this->user);

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
    }

    public function test_fifth_lesson_achievement()
    {
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

        /* @var Lesson $new_lesson */
        LessonWatched::dispatch($lessons->last(), $this->user);

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
    }
}
