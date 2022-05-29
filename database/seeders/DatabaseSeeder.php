<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Lesson::factory()
            ->count(20)
            ->create();

        $this->call([
            AchievementSeeder::class,
            BadgeSeeder::class,
        ]);
    }
}
