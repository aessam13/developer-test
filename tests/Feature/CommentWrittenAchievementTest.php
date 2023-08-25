<?php

namespace Tests\Feature;

use App\Enums\AchievementTypes;
use App\Events\CommentWritten;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Tests\TestCase;

class CommentWrittenAchievementTest extends TestCase
{
    public User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->seed(AchievementSeeder::class);
    }

    public function test_user_has_no_comments()
    {
        $comment = Comment::factory()->create();
        CommentWritten::dispatch($comment);
        $this->assertDatabaseMissing(
            'achievement_user',
            [
                'user_id' => $this->user->id,
            ]
        );
    }

    public function test_first_comment_achievement()
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $first_comment_achievement = Achievement::query()->where('action_count', 1)
            ->where('type', AchievementTypes::Comment)
            ->first();

        /* @var Comment $comment */
        CommentWritten::dispatch($comment);

        $this->assertDatabaseHas(
            'achievement_user',
            [
                'achievement_id' => $first_comment_achievement->id,
                'user_id' => $this->user->id,
            ]
        );
    }

    public function test_user_has_two_comments_so_first_comment_achievement_only_unlocked()
    {
        Comment::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $first_comment_achievement = Achievement::query()->where('action_count', 1)
            ->where('type', AchievementTypes::Comment)
            ->first();
        $this->user->achievements()->attach($first_comment_achievement);

        $new_comment = Comment::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $third_comments_achievement = Achievement::query()->where('action_count', 3)
            ->where('type', AchievementTypes::Comment)
            ->first();

        /* @var Comment $new_comment */
        CommentWritten::dispatch($new_comment);

        $this->assertDatabaseHas(
            'achievement_user',
            [
                'achievement_id' => $first_comment_achievement->id,
                'user_id' => $this->user->id,
            ]
        );

        $this->assertDatabaseMissing(
            'achievement_user',
            [
                'achievement_id' => $third_comments_achievement->id,
                'user_id' => $this->user->id,
            ]
        );
    }

    public function test_user_has_no_achievements()
    {
        $first_comment_achievement = Achievement::query()->where('action_count', 1)
            ->where('type', AchievementTypes::Comment)
            ->first();

        $this->assertDatabaseMissing(
            'achievement_user',
            [
                'achievement_id' => $first_comment_achievement->id,
                'user_id' => $this->user->id,
            ]
        );
    }

    public function test_third_comment_achievement()
    {
        Comment::factory()->count(2)->create([
            'user_id' => $this->user->id,
        ]);
        $first_comment_achievement = Achievement::query()->where('action_count', 1)
            ->where('type', AchievementTypes::Comment)
            ->first();
        $this->user->achievements()->attach($first_comment_achievement);

        $new_comment = Comment::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $third_comments_achievement = Achievement::query()->where('action_count', 3)
            ->where('type', AchievementTypes::Comment)
            ->first();

        /* @var Comment $new_comment */
        CommentWritten::dispatch($new_comment);

        $this->assertDatabaseHas(
            'achievement_user',
            [
                'achievement_id' => $third_comments_achievement->id,
                'user_id' => $this->user->id,
            ]
        );

        $this->assertDatabaseHas(
            'achievement_user',
            [
                'achievement_id' => $first_comment_achievement->id,
                'user_id' => $this->user->id,
            ]
        );
    }
}
