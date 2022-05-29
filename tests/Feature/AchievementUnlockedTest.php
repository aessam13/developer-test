<?php

namespace Tests\Feature;

use App\Enums\AchievementTypes;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Listeners\AchievementUnlockedListener;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Database\Seeders\BadgeSeeder;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AchievementUnlockedTest extends TestCase
{
    protected function setUp() : void
    {
        parent::setUp();
        $this->seed(AchievementSeeder::class);
        $this->seed(BadgeSeeder::class);
    }

    public function test_beginner_badge_for_a_user()
    {
        $beginner_badge = Badge::query()->where('achievements_number', 0)->first();

        $user = User::factory()->create();

        $this->assertDatabaseHas(
            'badge_user',
            [
                'user_id' => $user->id,
                'badge_id' => $beginner_badge->id,
            ]
        );
    }

    public function test_intermediate_badge_for_a_user()
    {
        /* @var User $user*/
        $user = User::factory()->create();

        $intermediate_badge = Badge::query()->where('achievements_number', 4)->first();

        $first_achievement = Achievement::query()->where('action_count', 1)
            ->where('type', AchievementTypes::Comment)
            ->first();

        $second_achievement = Achievement::query()->where('action_count', 1)
            ->where('type', AchievementTypes::Lesson)
            ->first();

        $third_achievement = Achievement::query()->where('action_count', 5)
            ->where('type', AchievementTypes::Lesson)
            ->first();

        $fourth_achievement = Achievement::query()->where('action_count', 3)
            ->where('type', AchievementTypes::Comment)
            ->first();

        $user->achievements()->attach($first_achievement);
        $user->achievements()->attach($second_achievement);
        $user->achievements()->attach($third_achievement);
        $user->achievements()->attach($fourth_achievement);

        AchievementUnlocked::dispatch($fourth_achievement->title, $user);

        $this->assertDatabaseHas(
            'badge_user',
            [
                'user_id' => $user->id,
                'badge_id' => $intermediate_badge->id,
            ]
        );
    }

    public function test_user_has_only_two_achievements()
    {
        $beginner_badge = Badge::query()->where('achievements_number', 0)->first();


        /* @var User $user*/
        $user = User::factory()->create();

        $first_achievement = Achievement::query()->where('action_count', 1)
            ->where('type', AchievementTypes::Comment)
            ->first();

        $second_achievement = Achievement::query()->where('action_count', 1)
            ->where('type', AchievementTypes::Lesson)
            ->first();

        $user->achievements()->attach($first_achievement);
        $user->achievements()->attach($second_achievement);

        AchievementUnlocked::dispatch($second_achievement->title, $user);


        $this->assertDatabaseHas(
            'badge_user',
            [
                'user_id' => $user->id,
                'badge_id' => $beginner_badge->id,
            ]
        );
    }
}
