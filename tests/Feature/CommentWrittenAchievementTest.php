<?php

namespace Tests\Feature;

use App\Enums\AchievementTypes;
use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Listeners\CommentWrittenListener;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentWrittenAchievementTest extends TestCase
{
    public User $user;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->seed(AchievementSeeder::class);
    }

    public function test_user_has_no_comments()
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

    public function test_first_comment_achievement()
    {
        Event::fake();

        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $first_comment_achievement = Achievement::query()->where('action_count', 1)
            ->where('type', AchievementTypes::Comment)
            ->first();

        /** @var Comment $comment */
        $comment_written_event = new CommentWritten($comment);
        $comment_written_listener = new CommentWrittenListener();
        $comment_written_listener->handle($comment_written_event);

        $this->assertDatabaseHas(
            'achievement_user',
            [
                'achievement_id' => $first_comment_achievement->id,
                'user_id' => $this->user->id,
            ]
        );

        Event::assertDispatched(function (AchievementUnlocked $event) use ($first_comment_achievement) {
            return $event->user->id == $this->user->id && $event->achievement_name == $first_comment_achievement->title;
        });
    }

    public function test_user_has_two_comments_so_first_comment_achievement_only_unlocked()
    {
        Event::fake();

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

        /** @var Comment $new_comment */
        $comment_written_event = new CommentWritten($new_comment);
        $comment_written_listener = new CommentWrittenListener();
        $comment_written_listener->handle($comment_written_event);

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

        Event::assertNotDispatched(AchievementUnlocked::class);
    }

    public function test_user_has_no_achievements()
    {
        Event::fake();

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

        Event::assertNotDispatched(AchievementUnlocked::class);
    }

    public function test_third_comment_achievement()
    {
        Event::fake();

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

        /** @var Comment $new_comment */
        $comment_written_event = new CommentWritten($new_comment);
        $comment_written_listener = new CommentWrittenListener();
        $comment_written_listener->handle($comment_written_event);

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

        Event::assertDispatched(function (AchievementUnlocked $event) use ($third_comments_achievement) {
            return $event->user->id == $this->user->id && $event->achievement_name == $third_comments_achievement->title;
        });
    }
}
