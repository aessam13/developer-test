<?php

namespace App\Models;

use App\Enums\AchievementTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The comments that belong to the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }

    /**
     * The achievements that a user has accomplished.
     */
    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class);
    }

    /**
     * The badges that a user has earned.
     */
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class);
    }

    public function next_available_achievements(): Collection
    {
        $current_comment_achievements = $this->achievements()->whereType(AchievementTypes::Comment)->orderByDesc('action_count')->first();
        $current_lesson_achievements = $this->achievements()->whereType(AchievementTypes::Lesson)->orderByDesc('action_count')->first();

        $next_available_comment_achievement = Achievement::whereType(AchievementTypes::Comment)
            ->where('action_count', '>', $current_comment_achievements ? $current_comment_achievements->action_count : 0)
            ->orderBy('action_count')
            ->first();

        $next_available_lesson_achievement = Achievement::whereType(AchievementTypes::Lesson)
            ->where('action_count', '>', $current_lesson_achievements ? $current_lesson_achievements->action_count : 0)
            ->orderBy('action_count')
            ->first();

        return collect(array_filter([ //to solve the 2 value equals to null
            $next_available_comment_achievement,
            $next_available_lesson_achievement,
        ]));
    }

    public function next_badge()
    {
        $current_badge = $this->badges()->orderBy('achievements_number')->get();

        $next_badge = Badge::where('achievements_number', '>', $current_badge->last()->achievements_number)
            ->orderBy('achievements_number')
            ->first();

        return $next_badge?->title;
    }

    public function remaining_to_unlock_next_badge(): int
    {
        $badges = $this->badges()->orderBy('achievements_number')->get();

        $next_badge = Badge::where('achievements_number', '>', $badges->last()->achievements_number)
            ->orderBy('achievements_number')
            ->first();

        return $next_badge ? $next_badge->achievements_number - $this->achievements()->count() : 0;
    }
}
