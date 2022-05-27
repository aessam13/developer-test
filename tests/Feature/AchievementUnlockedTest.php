<?php

namespace Tests\Feature;

use App\Enums\AchievementTypes;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Listeners\AchievementUnlockedListener;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AchievementUnlockedTest extends TestCase
{
    protected function setUp() : void
    {
        parent::setUp();
    }

    public function test_beginner_badge_for_a_user()
    {
        $beginner_badge = Badge::factory()->create([
            'title' => 'Beginner',
            'number' => 0,
        ]);

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
        Event::fake();

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
        $user->achievements()->attach($third_achievement);
        $user->achievements()->attach($fourth_achievement);

        $achievement_event = new AchievementUnlocked($fourth_achievement->title, $user);

        $achievement_unlocked_listener = new AchievementUnlockedListener();
        $achievement_unlocked_listener->handle($achievement_event);

        $this->assertDatabaseHas(
            'badge_user',
            [
                'user_id' => $user->id,
                'badge_id' => $intermediate_badge->id,
            ]
        );

        Event::assertDispatched(function (BadgeUnlocked $event) use ($user, $intermediate_badge) {
            return $event->user->id == $user->id && $event->badge_name == $intermediate_badge->title;
        });
    }

    public function test_user_has_only_two_achievements()
    {
        $beginner_badge = Badge::factory()->create([
            'title' => 'Beginner',
            'number' => 0,
        ]);

        /* @var User $user*/
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

        $user->achievements()->attach($first_achievement);
        $user->achievements()->attach($second_achievement);

        $achievement_event = new AchievementUnlocked($second_achievement->title, $user);

        $achievement_unlocked_listener = new AchievementUnlockedListener();
        $achievement_unlocked_listener->handle($achievement_event);

        $this->assertDatabaseHas(
            'badge_user',
            [
                'user_id' => $user->id,
                'badge_id' => $beginner_badge->id,
            ]
        );
    }
}
