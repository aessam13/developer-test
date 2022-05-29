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
            'number' => 1,
            'type' => AchievementTypes::Lesson,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '5 Lesson Watched',
            'number' => 5,
            'type' => AchievementTypes::Lesson,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '10 Lesson Watched',
            'number' => 10,
            'type' => AchievementTypes::Lesson,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '25 Lesson Watched',
            'number' => 25,
            'type' => AchievementTypes::Lesson,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '50 Lesson Watched',
            'number' => 50,
            'type' => AchievementTypes::Lesson,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => 'First Comment Written',
            'number' => 1,
            'type' => AchievementTypes::Comment,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '3 Comments Written',
            'number' => 3,
            'type' => AchievementTypes::Comment,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '5 Comments Written',
            'number' => 5,
            'type' => AchievementTypes::Comment,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '10 Comments Written',
            'number' => 10,
            'type' => AchievementTypes::Comment,
        ]);

        Achievement::query()->updateOrCreate([
            'title' => '20 Comments Written',
            'number' => 20,
            'type' => AchievementTypes::Comment,
        ]);
    }
}
