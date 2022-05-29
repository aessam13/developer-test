<?php

namespace Tests\Feature;

use App\Models\Badge;
use App\Models\User;
use Database\Seeders\BadgeSeeder;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected function setUp() : void
    {
        parent::setUp();
        $this->seed(BadgeSeeder::class);
    }

    public function test_user_has_beginner_badge_when_he_creates_an_account()
    {
        /* @var User $user*/
        $user = User::factory()->create();
        $beginner_badge = Badge::query()->where('achievements_number', 0)->first();

        $response = $this->getJson('/users/' . $user->id . '/achievements');
        $this->assertEquals($beginner_badge->title, $response->json('current_badge'));
    }

    public function test_user_created_when_use_factory()
    {
        /* @var User $user*/
        $user = User::factory()->create();

        $this->assertDatabaseHas(
            'users',
            [
                'id' => $user->id,
            ]
        );
    }
}
