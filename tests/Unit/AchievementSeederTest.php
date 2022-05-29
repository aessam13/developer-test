<?php

namespace Tests\Unit;

use Database\Seeders\AchievementSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AchievementSeederTest extends TestCase
{
    use DatabaseTransactions;

    public function test_if_the_seeder_will_create_the_data_only_one_time()
    {
        $this->seed(AchievementSeeder::class);
        $this->seed(AchievementSeeder::class);
        $this->seed(AchievementSeeder::class);

        $this->assertDatabaseCount('achievements', 10);
    }
}
