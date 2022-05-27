<?php

namespace Tests\Feature;

use App\Enums\AchievementTypes;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use Tests\TestCase;

class AchievementsControllerTest extends TestCase
{
    public function test_user_achievements()
    {
        $user = User::factory()->create();

        $first_achievement = Achievement::factory()->create([
            'title' => 'First Comment Written',
            'number' => 1,
            'type' => AchievementTypes::Comment,
        ]);

        $second_achievement = Achievement::factory()->create([
            'title' => 'First Lesson Watched',
            'number' => 1,
            'type' => AchievementTypes::Lesson,
        ]);

        //TODO: this is useful after add seeder to DatabaseSeeder ?
        $third_achievement = Achievement::factory()->create([
            'title' => '5 Lesson Watched',
            'number' => 5,
            'type' => AchievementTypes::Lesson,
        ]);

        $fourth_achievement = Achievement::factory()->create([
            'title' => '3 Comment Written',
            'number' => 3,
            'type' => AchievementTypes::Comment,
        ]);

        $user->achievements()->attach($first_achievement);
        $user->achievements()->attach($second_achievement);

        $beginner_badge = Badge::factory()->create([
            'title' => 'Beginner',
            'number' => 0,
        ]);

        $intermediate_badge = Badge::factory()->create([
            'title' => 'Intermediate',
            'number' => 4,
        ]);

        $user->badges()->attach($beginner_badge);

        $response = $this->getJson('/users/' . $user->id . '/achievements');

        $response->assertSee($first_achievement->title);
        $response->assertSee($second_achievement->title);

        $this->assertEquals($intermediate_badge->title, $response->json('next_badge'));
    }

    public function test_user_has_the_last_badge()
    {
        $user = User::factory()->create();

        $fifth_comment_achievement = Achievement::factory()->create([
            'title' => 'Fifth Comment Written',
            'number' => 5,
            'type' => AchievementTypes::Comment,
        ]);

        $fifth_lesson_achievement = Achievement::factory()->create([
            'title' => 'Fifth Lesson Watched',
            'number' => 5,
            'type' => AchievementTypes::Lesson,
        ]);

        $user->achievements()->attach($fifth_comment_achievement);
        $user->achievements()->attach($fifth_lesson_achievement);

        $master_badge = Badge::factory()->create([
            'title' => 'Master',
            'number' => 10,
        ]);

        $user->badges()->attach($master_badge);

        $response = $this->getJson('/users/' . $user->id . '/achievements');

        $response->assertSee($fifth_lesson_achievement->title);
        $response->assertSee($fifth_comment_achievement->title);

        $this->assertNull($response->json('next_badge'));
        $this->assertEmpty($response->json('next_available_achievements'));
    }

    public function test_user_has_no_achievements()
    {
        $beginner_badge = Badge::factory()->create([
            'title' => 'Beginner',
            'number' => 0,
        ]);

        $user = User::factory()->create();

        $intermediate_badge = Badge::factory()->create([
            'title' => 'Intermediate',
            'number' => 4,
        ]);

        $first_achievement = Achievement::factory()->create([
            'title' => 'First Comment Written',
            'number' => 1,
            'type' => AchievementTypes::Comment,
        ]);

        $second_achievement = Achievement::factory()->create([
            'title' => 'First Lesson Watched',
            'number' => 1,
            'type' => AchievementTypes::Lesson,
        ]);

        $response = $this->getJson('/users/' . $user->id . '/achievements');

        $this->assertEmpty($response->json('unlocked_achievements'));
        $response->assertSee($first_achievement->title);
        $response->assertSee($second_achievement->title);

        $this->assertEquals($intermediate_badge->title, $response->json('next_badge'));
    }

    public function test_remaining_to_unlock_next_badge()
    {
        $beginner_badge = Badge::factory()->create([
            'title' => 'Beginner',
            'number' => 0,
        ]);

        $user = User::factory()->create();

        $intermediate_badge = Badge::factory()->create([
            'title' => 'Intermediate',
            'number' => 4,
        ]);

        $response = $this->getJson('/users/' . $user->id . '/achievements');

        $this->assertEquals(4, $response->json('remaining_to_unlock_next_badge'));
    }

    public function test_remaining_to_unlock_next_badge_if_user_watch_only_one_lesson()
    {
        $beginner_badge = Badge::factory()->create([
            'title' => 'Beginner',
            'number' => 0,
        ]);

        $user = User::factory()->create();

        $intermediate_badge = Badge::factory()->create([
            'title' => 'Intermediate',
            'number' => 4,
        ]);

        $first_achievement = Achievement::factory()->create([
            'title' => 'First Lesson Watched',
            'number' => 1,
            'type' => AchievementTypes::Lesson,
        ]);

        $second_achievement = Achievement::factory()->create([
            'title' => '5 Lesson Watched',
            'number' => 5,
            'type' => AchievementTypes::Lesson,
        ]);

        $user->achievements()->attach($first_achievement);
        $user->badges()->attach($beginner_badge);

        $response = $this->getJson('/users/' . $user->id . '/achievements');

        $this->assertEquals(3, $response->json('remaining_to_unlock_next_badge'));
    }
}
