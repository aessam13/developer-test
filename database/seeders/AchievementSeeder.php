<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('achievements')->insert([
            'title' => 'First Lesson Watched',
            'number' => 1,
            'type' => Achievement::LESSON,
        ]);

        DB::table('achievements')->insert([
            'title' => '5 Lesson Watched',
            'number' => 5,
            'type' => Achievement::LESSON,
        ]);

        DB::table('achievements')->insert([
            'title' => '10 Lesson Watched',
            'number' => 10,
            'type' => Achievement::LESSON,
        ]);

        DB::table('achievements')->insert([
            'title' => '25 Lesson Watched',
            'number' => 25,
            'type' => Achievement::LESSON,
        ]);

        DB::table('achievements')->insert([
            'title' => '50 Lesson Watched',
            'number' => 50,
            'type' => Achievement::LESSON,
        ]);

        DB::table('achievements')->insert([
            'title' => 'First Comment Written',
            'number' => 1,
            'type' => Achievement::COMMENT,
        ]);

        DB::table('achievements')->insert([
            'title' => '3 Comments Written',
            'number' => 3,
            'type' => Achievement::COMMENT,
        ]);

        DB::table('achievements')->insert([
            'title' => '5 Comments Written',
            'number' => 5,
            'type' => Achievement::COMMENT,
        ]);

        DB::table('achievements')->insert([
            'title' => '10 Comments Written',
            'number' => 10,
            'type' => Achievement::COMMENT,
        ]);

        DB::table('achievements')->insert([
            'title' => '20 Comments Written',
            'number' => 20,
            'type' => Achievement::COMMENT,
        ]);
    }
}
