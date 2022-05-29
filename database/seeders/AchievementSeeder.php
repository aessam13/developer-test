<?php

namespace Database\Seeders;

use App\Enums\AchievementTypes;
use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Achievement::query()->updateOrCreate([
            'title' => 'First Lesson Watched',
            'action_count' => 1,
            'type' => AchievementTypes::Lesson,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '5 Lessons Watched',
            'action_count' => 5,
            'type' => AchievementTypes::Lesson,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '10 Lessons Watched',
            'action_count' => 10,
            'type' => AchievementTypes::Lesson,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '25 Lessons Watched',
            'action_count' => 25,
            'type' => AchievementTypes::Lesson,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '50 Lessons Watched',
            'action_count' => 50,
            'type' => AchievementTypes::Lesson,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => 'First Comment Written',
            'action_count' => 1,
            'type' => AchievementTypes::Comment,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '3 Comments Written',
            'action_count' => 3,
            'type' => AchievementTypes::Comment,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '5 Comments Written',
            'action_count' => 5,
            'type' => AchievementTypes::Comment,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '10 Comments Written',
            'action_count' => 10,
            'type' => AchievementTypes::Comment,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '20 Comments Written',
            'action_count' => 20,
            'type' => AchievementTypes::Comment,
        ]);
    }
}
