<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('badges')->insert([
            'title' => 'Beginner',
            'number' => 0,
        ]);

        DB::table('badges')->insert([
            'title' => 'Intermediate',
            'number' => 4,
        ]);

        DB::table('badges')->insert([
            'title' => 'Advanced',
            'number' => 8,
        ]);

        DB::table('badges')->insert([
            'title' => 'Master',
            'number' => 10,
        ]);
    }
}
