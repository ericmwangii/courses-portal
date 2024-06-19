<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Achievement::create(['name' => 'First Comment Written', 'type' => 'comment', 'threshold' => 1]);
        Achievement::create(['name' => '3 Comments Written', 'type' => 'comment', 'threshold' => 3]);
        Achievement::create(['name' => '5 Comments Written', 'type' => 'comment', 'threshold' => 5]);
        Achievement::create(['name' => '10 Comments Written', 'type' => 'comment', 'threshold' => 10]);
        Achievement::create(['name' => '20 Comments Written', 'type' => 'comment', 'threshold' => 20]);

        Achievement::create(['name' => 'First Video Watched', 'type' => 'lesson', 'threshold' => 1]);
        Achievement::create(['name' => '5 Lessons Watched', 'type' => 'lesson', 'threshold' => 5]);
        Achievement::create(['name' => '10 Lessons Watched', 'type' => 'lesson', 'threshold' => 10]);
        Achievement::create(['name' => '25 Lessons Watched', 'type' => 'lesson', 'threshold' => 25]);
        Achievement::create(['name' => '50 Lessons Watched', 'type' => 'lesson', 'threshold' => 50]);
    }
}
