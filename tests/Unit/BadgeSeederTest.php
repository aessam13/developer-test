<?php

namespace Tests\Unit;

use Database\Seeders\AchievementSeeder;
use Database\Seeders\BadgeSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BadgeSeederTest extends TestCase
{
    use DatabaseTransactions;

    public function test_if_the_seeder_will_create_the_data_only_one_time()
    {
        $this->seed(BadgeSeeder::class);
        $this->seed(BadgeSeeder::class);
        $this->seed(BadgeSeeder::class);

        $this->assertDatabaseCount('badges', 4);
    }
}
