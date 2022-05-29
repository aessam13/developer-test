<?php

namespace Tests\Feature;

use App\Enums\AchievementTypes;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Database\Seeders\BadgeSeeder;
use Tests\TestCase;

class AchievementsControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AchievementSeeder::class);
        $this->seed(BadgeSeeder::class);
    }

    public function test_user_achievements()
    {
        /* @var User $user*/
        $user = User::factory()->create();

        $first_achievement = Achievement::query()->where('number', 1)
            ->where('type', AchievementTypes::Comment)
            ->first();

        $second_achievement = Achievement::query()->where('number', 1)
            ->where('type', AchievementTypes::Lesson)
            ->first();

        $user->achievements()->attach($first_achievement);
        $user->achievements()->attach($second_achievement);

        $intermediate_badge = Badge::query()->where('number', 4)->first();

        $response = $this->getJson('/users/' . $user->id . '/achievements');

        $this->assertEquals($first_achievement->title, $response->json('unlocked_achievements')[0]);
        $this->assertEquals($second_achievement->title, $response->json('unlocked_achievements')[1]);
        $this->assertEquals($intermediate_badge->title, $response->json('next_badge'));
    }

    public function test_user_has_the_last_badge()
    {
        /* @var User $user*/
        $user = User::factory()->create();

        $last_comment_achievement = Achievement::query()->where('number', 20)
            ->where('type', AchievementTypes::Comment)
            ->first();

        $last_lesson_achievement = Achievement::query()->where('number', 50)
            ->where('type', AchievementTypes::Lesson)
            ->first();

        $user->achievements()->attach($last_comment_achievement);
        $user->achievements()->attach($last_lesson_achievement);

        $master_badge = Badge::query()->where('number', 10)->first();

        $user->badges()->attach($master_badge);

        $response = $this->getJson('/users/' . $user->id . '/achievements');

        $this->assertEquals($last_comment_achievement->title, $response->json('unlocked_achievements')[0]);
        $this->assertEquals($last_lesson_achievement->title, $response->json('unlocked_achievements')[1]);
        $this->assertEquals($master_badge->title, $response->json('current_badge'));
        $this->assertNull($response->json('next_badge'));
        $this->assertEquals(0, $response->json('remaining_to_unlock_next_badge'));
        $this->assertEmpty($response->json('next_available_achievements'));
    }

    public function test_user_has_no_achievements()
    {
        /* @var User $user*/
        $user = User::factory()->create();

        $intermediate_badge = Badge::query()->where('number', 4)->first();

        $first_achievement = Achievement::query()->where('number', 1)
            ->where('type', AchievementTypes::Comment)
            ->first();

        $second_achievement = Achievement::query()->where('number', 1)
            ->where('type', AchievementTypes::Lesson)
            ->first();

        $response = $this->getJson('/users/' . $user->id . '/achievements');

        $this->assertEmpty($response->json('unlocked_achievements'));
        $this->assertEquals($first_achievement->title, $response->json('next_available_achievements')[0]);
        $this->assertEquals($second_achievement->title, $response->json('next_available_achievements')[1]);
        $this->assertEquals($intermediate_badge->title, $response->json('next_badge'));
    }

    public function test_remaining_to_unlock_next_badge()
    {
        /* @var User $user*/
        $user = User::factory()->create();

        $response = $this->getJson('/users/' . $user->id . '/achievements');

        $this->assertEquals(4, $response->json('remaining_to_unlock_next_badge'));
    }

    public function test_remaining_to_unlock_next_badge_if_user_watch_only_one_lesson()
    {
        /* @var User $user*/
        $user = User::factory()->create();

        $first_achievement = Achievement::query()->where('number', 1)
            ->where('type', AchievementTypes::Lesson)
            ->first();
        $user->achievements()->attach($first_achievement);

        $response = $this->getJson('/users/' . $user->id . '/achievements');

        $this->assertEquals(3, $response->json('remaining_to_unlock_next_badge'));
    }
}
