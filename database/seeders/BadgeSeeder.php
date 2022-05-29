<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Badge::query()->updateOrCreate([
            'title' => 'Beginner',
            'achievements_number' => 0,
        ]);

        Badge::query()->updateOrCreate([
            'title' => 'Intermediate',
            'achievements_number' => 4,
        ]);

        Badge::query()->updateOrCreate([
            'title' => 'Advanced',
            'achievements_number' => 8,
        ]);

        Badge::query()->updateOrCreate([
            'title' => 'Master',
            'achievements_number' => 10,
        ]);
    }
}
