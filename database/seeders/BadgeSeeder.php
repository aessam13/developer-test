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
            'number' => 0,
        ]);

        Badge::query()->updateOrCreate([
            'title' => 'Intermediate',
            'number' => 4,
        ]);

        Badge::query()->updateOrCreate([
            'title' => 'Advanced',
            'number' => 8,
        ]);

        Badge::query()->updateOrCreate([
            'title' => 'Master',
            'number' => 10,
        ]);
    }
}
